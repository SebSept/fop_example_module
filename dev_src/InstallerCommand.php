<?php
/**
 * InstallerCommand.php examplemodule
 *
 * @author Sébastien Monterisi <contact@seb7.fr>
 */
// c'est du brutal ici !
// @todo copier les config (avec filesystem)
// @todo faire une commande symfony (pour gérer des options et gerer des questions)
// @todo utiliser filesystem pour lancer des exceptions.
// @todo linter le composer.json final au besoin.

namespace FriendsOfPresta\BaseModuleInstaller;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class InstallerCommand extends Command
{
    const CONFIG_TO_COPY = ['.github/workflows/', '.php_cs', 'grumphp.yml.dist', 'phpstan.neon.dist'];
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
            $this->copyConfigurationFiles(!$input->getOption('no-interaction'));

            return Command::SUCCESS; /* @phpstan-ignore-line */
        } catch (\Exception $exception) {
            // @todo formatage d'erreur
            $this->output->writeln('Installation aborted : ' . $exception->getMessage(), OutputInterface::VERBOSITY_QUIET);

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
}
