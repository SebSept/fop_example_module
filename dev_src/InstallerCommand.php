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
use Symfony\Component\Console\Question\Question;
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
        $this->welcomeMessage();

        $this->insertComposerScripts(!$input->getOption('no-interaction'));
        $this->copyConfiguration(!$input->getOption('no-interaction'));
        $output->writeln('hello');

        return Command::SUCCESS; /* @phpstan-ignore-line */
    }

    private function welcomeMessage(): void
    {
        $formatter = $this->getHelper('formatter');
        $welcome_text = $formatter->formatBlock('Welcome to Friends Of Presta Module development tools installer.', 'info');
        $this->output->writeln($welcome_text);
        $this->output->writeln(['No files will be erased.', 'Modified or replaced files are copied using the suffix .backup.\<timestamp>']);
    }

    /**
     * Insert Tools scripts into composer.json
     *
     * @param bool $interaction
     */
    private function insertComposerScripts(bool $interaction): void
    {
        // composer du projet dans lequel la commande est lancée : la cible
        $project_composer_path = __DIR__ . '/../../../../composer.json';
        // composer du package actuel : la source
        $fop_package_composer_path = __DIR__ . '/../composer.json';
        $fs = new Filesystem();
        $questioner = $this->getHelper('question');

        // backup composer
        $question = new Question('Insert tools in composer.json ? (yes)', 'yes');
        if (!$interaction || 'yes' === $questioner->ask($this->input, $this->output, $question)) {
            if (!realpath($project_composer_path)) {
                $this->output->writeln($fs->makePathRelative($project_composer_path, __DIR__));
                throw new \Exception('Source file not found : ' . $project_composer_path);
            }

            $composer_backup = $project_composer_path . 'backup.' . date('U');
            $fs->copy($project_composer_path, $composer_backup);
            $this->output->writeln("composer.json backup created ($composer_backup)", OutputInterface::VERBOSITY_QUIET);
        } else {
            $this->output->writeln('composer.json not backed up.', OutputInterface::VERBOSITY_VERBOSE);
        }

        trigger_error('implement the rest : see composer/JsonManipulator');

        return;
        // extraction composer du package
        $fop_package_composer_json = json_decode(file_get_contents($fop_package_composer_path));
        if (false === $fop_package_composer_json) {
            throw new Exception('Pas possible de decoder ' . $fop_package_composer_path);
        }

        // extraction composer cible
        $project_composer_json = json_decode(file_get_contents($project_composer_path));
        if (false === $project_composer_json) {
            throw new Exception('Pas possible de decoder ' . $project_composer_path);
        }

        // insertion des scripts
        foreach ((array) $fop_package_composer_json->scripts as $script_name => $script_command) {
            $project_composer_json->scripts->$script_name = $script_command;
        }

        // enregistrement du nouveau composer.json
        $new_composer_content = json_encode(($project_composer_json));
        file_put_contents($project_composer_path . '.test.json', $new_composer_content);
    }

    private function copyConfiguration(bool $interaction = false): void
    {
        throw new \Exception('Not implemented');
    }
}

return;

insert_composer_scripts(); // déclarer les bin dans le bin composer : a faire dans composer
copy_config();

// copier les config avec demande de confirmation  ou création auto de backup
