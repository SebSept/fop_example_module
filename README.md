# About

This repository serve as a start point for Friends of Presta modules and codes.
It's configured with quality tools and checks tools.

You have only one configuration to do : provide a path to a Prestashop installation for phpstan to work properly.

Everything needed is included in this repository you can just run `./vendor/bin/grumphp run`


## Coding standards

Codes and documents on Friends of Presta targets [_PrestaShop_ ](https://github.com/prestashop/prestashop), we must use the same coding and writing rules.

Prestashop follows guidelines using tools and configuration to ensure consistency.


For details, you can read [the DevDocs coding standards](https://devdocs.prestashop.com/1.7/development/coding-standards/)

### Php-cs-fixer

[php-cs-fixer](https://github.com/FriendsOfPhp/PHP-CS-Fixer) fixes your code to follow standards.

It's installed via [composer](https://devdocs.prestashop.com/1.7/modules/concepts/composer/) using this [composer.json file](composer.json) and configured using [.php_cs.dist](.php_cs.dist). 
The php-cs configuration also requires [prestashop/php-dev-tools](https://github.com/prestashop/php-dev-tools).

To run only fixer alone : `php vendor/bin/php-cs-fixer fix`

### php-stan

@todo bla bla

` _PS_ROOT_DIR_=</path/to/prestashop/> php ./vendor/bin/phpstan.phar analyse ./`

## Go further

Lots of information are available at [Prestashop DevDocs](https://devdocs.prestashop.com).

## Legal

_Prestashop_ is a registred tradmark of [Prestashop](https://www.prestashop.com).
