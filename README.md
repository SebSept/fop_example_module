# About

This repository serve as a start point for a building a quality Prestashop module.
It's pre-configured with tools to ensure that the code complies with the standards and is valid. 

It use pre-commit validation and github actions.

It relies on prestashop tools. Prestashop tools relies on classic tools (php-cs-fix, phpstan and more (to come)).

Read after 'Getting started' for more information.
 
 # Getting started

Only 2 steps are required.
## 1 : Install via composer
 
 `composer create-project friends-of-presta/examplemodule --repository "{\"type\": \"vcs\", \"url\": \"https://github.com/SebSept/fop_example_module\"}" --stability=dev`
 
> This is a temporary install, final will be `composer create-project friends-of-presta/examplemodule --stability=dev`

## 2 : Configuration

Edit `grumphp.yml` file, replace `/path/to/your/prestashop/` with ... a path to a Prestashop directory

You are ready to go !

Before each commit, the lints and checks will run without nothing more to do.
Each push on the github repository (you have to it yourself), the checks in [workflows](.github/workflows) will be triggered.

If something is wrong commit is aborted.

You can also run all the checks and lints by running a single command : `composer run check`

## Coding standards

Codes and documents on Friends of Presta targets [_PrestaShop_ ](https://github.com/prestashop/prestashop), we must use the same coding and writing rules.

Prestashop follows guidelines using tools and configuration to ensure consistency.

For details, you can read [the DevDocs coding standards](https://devdocs.prestashop.com/1.7/development/coding-standards/)

##Preinstalled tools

### Php-cs-fixer

[php-cs-fixer](https://github.com/FriendsOfPhp/PHP-CS-Fixer) fixes your code to follow standards.

It's installed via [composer](https://devdocs.prestashop.com/1.7/modules/concepts/composer/) using this [composer.json file](composer.json) and configured using [.php_cs.dist](.php_cs.dist). 
The php-cs configuration also requires [prestashop/php-dev-tools](https://github.com/prestashop/php-dev-tools).

To run only fixer alone : `php vendor/bin/php-cs-fixer fix`

### php-stan

@todo bla bla

` _PS_ROOT_DIR_=</path/to/prestashop/> php ./vendor/bin/phpstan.phar analyse ./`

### Troubleshooting

If you changed the path to Prestashop in grumphp.yml, you may need to clear the phpstan cache.
Process deleting its cache in php temp dir (`/tmp/` on linux, use `sys_get_temp_dir()` to find it.).

## Go further

Lots of information are available at [Prestashop DevDocs](https://devdocs.prestashop.com).

## Legal

_Prestashop_ is a registred tradmark of [Prestashop](https://www.prestashop.com).
