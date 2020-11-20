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
    const CONFIG_TO_COPY = ['.github/workflows/', '.php_cs', 'grumphp.yml', 'phpstan.neon.dist'];
    /**
     * @var OutputInterface
     */
    private $output;
    /**
     * @var InputInterface
     */
    private $input;

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
        $fs = new Filesystem();
        foreach (self::CONFIG_TO_COPY as $spl_file) {
            $this->copySplFile(new \SplFileInfo(__DIR__ . '/../' . $spl_file));
        }
    }

    private function copySplFile(\SplFileInfo $file_info)
    {
        if (!$file_info->isFile() && !$file_info->isDir()) {
//            \dump($file_info);
            throw new \Exception('oops Not a dir & not a file ' . $file_info->getFilename()); // @todo oops
        }

        if (!$file_info->isDir()) {
            // @todo ecrire fonction de copie
            $this->output->writeln('copie du fichier ' . $file_info->getFilename());

            return;
        }

        $this->output->writeln('copie du dossier par récursion ...' . $file_info->getFilename());
        foreach ($file_info as $sub_file_info) {
            $this->output->writeln('sub : ' . $sub_file_info->getFilename());

            // @todo ecrire fonction de création de dossier
            $this->copySplFile($sub_file_info);
        }
    }
}
