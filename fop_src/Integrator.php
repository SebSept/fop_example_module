<?php
/**
 * Copyright (c) Since 2020 Friends of Presta
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file docs/licenses/LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    Friends of Presta <infos@friendsofpresta.org>
 * @copyright since 2020 Friends of Presta
 * @license   https://opensource.org/licenses/AFL-3.0  Academic Free License ("AFL") v. 3.0
 */

declare(strict_types=1);

namespace FriendsOfPresta\BaseModuleInstaller;

require_once __DIR__ . '/../../../../autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Filesystem\Filesystem;

class Integrator
{
    public static function integrate(?string $base_path): void
    {
        $base_path = $base_path ?? __DIR__;
        $base_path = trim($base_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        try {
            self::createGrumphpConfigFile($base_path);
            self::configureGrumphp($base_path);
        } catch (\Exception $exception) {
            echo 'Error integrating grumphp : ' . $exception->getMessage();
            exit(1);
        }
    }

    private static function createGrumphpConfigFile(string $base_path): void
    {
        $fs = new Filesystem();
        $fs->copy($base_path . 'grumphp.yml.dist', $base_path . 'grumphp.yml');
        echo sprintf("File 'grumphp.yml' created at %s", $base_path);
    }

    private static function configureGrumphp(string $base_path): void
    {
        $application = new Application();
        $command = new GrumphpConfigurationCommand();

        $application->add($command);
        $application->setDefaultCommand($command->getName());
        $application->run(new ArrayInput(['base-path' => $base_path]));
    }
}
