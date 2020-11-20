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

namespace FriendsOfPresta\BaseModuleInstaller;

use Symfony\Component\Filesystem\Filesystem;

class Integrator
{
    public static function integrate(): void
    {
        $fs = new Filesystem();
        $fs->copy('grumphp.yml.dist', 'grumphp.yml');
        echo "Fichier 'grumphp.yml' créé." . PHP_EOL . 'Definissez _PS_ROOT_DIR_ avec le chemin vers une installation de prestashop.';
        echo "File 'grumphp.yml' created." . PHP_EOL . 'Define _PS_ROOT_DIR_ with the path to a prestashop installation.';
    }
}
