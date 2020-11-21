<?php
/**
 * InstallerCommand.php examplemodule
 *
 * @author Sébastien Monterisi <contact@seb7.fr>
 */
// @todo faire une commande symfony (pour gérer des options et gerer des questions)
// @todo linter le composer.json final au besoin.

namespace FriendsOfPresta\BaseModuleInstaller;

use Composer\Json\JsonManipulator;
use http\Exception\RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

final class InstallerCommand extends Command
{
    const CONFIG_TO_COPY = ['.github/workflows/', '.php_cs.dist', 'grumphp.yml.dist', 'phpstan.neon.dist'];
    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var Filesystem
     */
    private $fs;

    protected function configure(): void
    {
        $this->setName('install');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        try {
            $this->displayWelcomeMessage();
            trigger_error('reactiver ces étapes');
//            $this->copyConfigurationFiles(!$input->getOption('no-interaction'));
//            $this->initGrumphp();
            $this->insertComposerScripts(!$input->getOption('no-interaction'));

            return Command::SUCCESS; /* @phpstan-ignore-line */
        } catch (\Exception $exception) {
            $this->output->writeln('<fg=red>Installation aborted : ' . $exception->getMessage() . '<fg/>', OutputInterface::VERBOSITY_QUIET);

            return Command::FAILURE; /* @phpstan-ignore-line */
        }
    }

    private function displayWelcomeMessage(): void
    {
        $formatter = $this->getHelper('formatter');
        $welcome_text = $formatter->formatBlock('Welcome to Friends Of Presta Module development tools installer.', 'info');
        $this->output->writeln($welcome_text);
        $this->output->writeln(['No files will be erased.', 'Modified or replaced files are copied using the suffix .backup.\<timestamp>']);
    }

    private function copyConfigurationFiles(bool $interaction = false): void
    {
        $this->output->writeln('Begining copying configuration files...', OutputInterface::VERBOSITY_VERBOSE);
        $this->fs = new Filesystem();
        foreach (self::CONFIG_TO_COPY as $spl_file) {
            $this->copySplFile(new \SplFileInfo(__DIR__ . '/../' . $spl_file));
        }
    }

    private function copySplFile(\SplFileInfo $file_info)
    {
        if (!$file_info->isFile() && !$file_info->isDir()) {
            \dump($file_info);
            throw new \LogicException('File to copy not found in sources ! Contact the developpers. File : ' . $file_info->getFilename());
        }

        if (!$file_info->isDir()) {
            $this->output->write('Copy file ' . $file_info->getFilename(), false, OutputInterface::VERBOSITY_NORMAL);
            $this->output->writeln(' to ' . __DIR__ . '/' . $this->destinationPath($file_info), OutputInterface::VERBOSITY_NORMAL);
            // does not copy if exists and is newer
            $this->fs->copy($file_info->getPathname(), __DIR__ . '/' . $this->destinationPath($file_info));

            return;
        }

        $this->output->writeln(' Now seeking ' . $file_info->getFilename(), OutputInterface::VERBOSITY_VERBOSE);
        // create directory if does not exist
        $destination_directory_path = $this->destinationPath($file_info);
        // maybe no checks are needed ...
        $destination = new \SplFileInfo($destination_directory_path);
        if (!$destination->isDir() && !$destination->isFile() && !$destination->isLink()) {
            $this->output->writeln('Creating directory ' . __DIR__ . '/' . $destination_directory_path, OutputInterface::VERBOSITY_VERBOSE);
            $this->fs->mkdir(__DIR__ . '/' . $destination_directory_path);
        }

        // treat files and dir in that directory, recursion
        $sub_dir = new \FilesystemIterator($file_info->getPathname());
        foreach ($sub_dir as $sub_file_info) {
            $this->copySplFile($sub_file_info);
        }
    }

    private function destinationPath(\SplFileInfo $source): string
    {
        return '../../../../' . rtrim($this->fs->makePathRelative($source->getPathname(), __DIR__ . '/../'), '/');
    }

    private function initGrumphp()
    {
        $this->output->write('Intalling grumphp : ');
        // launch throught process. (any idea how to simply call the GrumPHP\Console\Command\Git\InitCommand() ?
        $process = new Process(['./vendor/bin/grumphp', 'git:init'], __DIR__ . '/../../../../');
        $process->run();
        if (!$process->isSuccessful()) {
            throw new \Exception('Error running grumphp git:init : ' . $process->getErrorOutput());
        }
        $this->output->writeln($process->getOutput());
    }

    private function insertComposerScripts(bool $interaction): void
    {
        $target_composer_path = __DIR__ . '/../../../../composer.json';
        $target_composer_path_backup = sprintf('%s/../../../../composer.%s.json', __DIR__, date('U'));
        $source_composer_path = __DIR__ . '/../composer.json';
        if (!realpath($target_composer_path)) {
            throw new \LogicException(__FUNCTION__ . ' : Target composer not found at ' . $target_composer_path);
        }
        if (!realpath($source_composer_path)) {
            throw new \LogicException(__FUNCTION__ . ' : Source composer not found at ' . $source_composer_path);
        }

        $fs = new Filesystem();
        $questioner = $this->getHelper('question');

        // backup composer
        $question = new Question('Insert tools\' scripts in composer.json ? (yes)', 'yes');
        if (!$interaction || 'yes' === $questioner->ask($this->input, $this->output, $question)) {
            $fs->copy($target_composer_path, $target_composer_path_backup);
            $this->output->writeln("composer.json backup created : $target_composer_path", OutputInterface::VERBOSITY_QUIET);
        } else {
            $this->output->writeln('composer.json not backed up.', OutputInterface::VERBOSITY_VERBOSE);
        }

        $source_scripts = json_decode(file_get_contents($source_composer_path)); // todo use symfony (de)serializer ?
        if (false === $source_scripts) {
            throw new \RuntimeException('Failed to jsondecode ' . $source_composer_path);
        }

        // extraction composer cible
//        $project_composer_json = json_decode(file_get_contents($target_composer_path));
//        if (false === $project_composer_json) {
//            throw new RuntimeException('Failed to jsondecode ' . $target_composer_path);
//        }
        $json_manipulator = new JsonManipulator(file_get_contents($target_composer_path));

        // insertion des scripts
        foreach ((array) $source_scripts->scripts as $script_name => $script_command) {
            $json_manipulator->addSubNode('scripts', $script_name, $script_command);
        }

        $fs->dumpFile($target_composer_path, $json_manipulator->getContents());
    }
}
