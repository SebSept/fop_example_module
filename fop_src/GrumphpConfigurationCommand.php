<?php
/**
 * PostInstallerCommand.php examplemodule
 *
 * @author Sébastien Monterisi <contact@seb7.fr>
 */

namespace FriendsOfPresta\BaseModuleInstaller;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Yaml\Yaml;

class GrumphpConfigurationCommand extends Command
{
    public function configure()
    {
        $this->addOption('base-path', 'd', InputOption::VALUE_REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $base_path = $input->getOption('base-path');

        $grumphp_yaml_path = $base_path . 'grumphp.yml';
        $yaml = Yaml::parseFile($grumphp_yaml_path);
        $output->writeln('A path to a Prestashop installation is needed by phpstan.');
        $question = new Question('Where is this prestashop ?');
        $questioner = $this->getHelper('question');
        $path = $questioner->ask($input, $output, $question);
        if (!$path) {
            $output->writeln('No _PS_ROOT_DIR_ path provided. grumphp configuration not modified.');
        }
        $yaml['grumphp']['config']['environment']['variables']['_PS_ROOT_DIR_'] = $path;

        if (!file_put_contents($grumphp_yaml_path, Yaml::dump($yaml))) {
            throw new \Exception("Failed writing modified yaml to $grumphp_yaml_path.");
        }

        return Command::SUCCESS; /* @phpstan-ignore-line */
    }
}
