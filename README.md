# About

This repository serve as a start point for a building a quality Prestashop module.
It's pre-configured with tools to ensure that the code complies with the standards and is valid. 

It uses pre-commit validation for local development and github actions for repository checks.

Before each commit, the lints and checks will run without nothing more to do.
Each push on the github repository (you have to it yourself), the checks in [workflows](.github/workflows) will be triggered.

If something is wrong commit is aborted.

You can also run all the checks and lints by running a single command : `composer run check`. (treat files added to commit)

It relies on [PrestaShop/php-dev-tools/](https://github.com/PrestaShop/php-dev-tools/). Which in turns, relies on wide spread tools (php-cs-fix, phpstan, and more (to come)).

Pre-commit actions are _bundled_ using [phpro/grumphp](https://github.com/phpro/grumphp).
Repository actions are made by [github workflows](https://docs.github.com/en/free-pro-team@latest/actions).
 
# Getting started

2 steps are required.

## 1 - Install via composer

Temporary install (before registration on packagist.org) :
 
```shell script
composer create-project friends-of-presta/examplemodule --repository "{\"type\": \"vcs\", \"url\": \"https://github.com/SebSept/fop_example_module\"}" --stability=dev
```
 
> This is a temporary install, final will be `composer create-project friends-of-presta/examplemodule --stability=dev`

## 2 - Configuration

Edit `grumphp.yml` file, replace `/path/to/your/prestashop/` with a path to a Prestashop directory.

You are ready to go !

# Coding standards and php tools

Codes and documents on Friends of Presta targets [_PrestaShop_ ](https://github.com/prestashop/prestashop), so it uses the same coding and writing rules.

For details, you can read [the DevDocs coding standards](https://devdocs.prestashop.com/1.7/development/coding-standards/)

## Preinstalled tools for local development

These tools are triggered automatically before each commit (except header-stamp).
They can also be launched on demand : `composer run check`.

Commands are registered as composer scripts.
To list commands : `composer run -l`

### Php-cs-fixer

[php-cs-fixer](https://github.com/FriendsOfPhp/PHP-CS-Fixer) fixes your code to follow standards.
It follows rules defined by [prestashop/php-dev-tools](https://github.com/prestashop/php-dev-tools).

> This command is triggered by `composer run check`.
> You don't need to trigger it separately.
> However, in case you want to you can, it is possible.

To run only fixer only : 
```
composer run php-cs
```

(Tip: define a command alias `alias cr="composer run"` so you just have to run `cr php-cs`).

### php-stan

[phpstan](https://phpstan.org/) _finds bugs in your code without writing tests._ It follows rules defines by Prestashop and use a bootstrap file provided by [prestashop/php-dev-tools](https://github.com/prestashop/php-dev-tools).

> This command is triggered by `composer run check`.
> You don't need to trigger it separately.
> However, in case you want to you can, it is possible.

#### Notice 

By default `composer run check` use a phpstan configuration that check files independently of there git state (added to index or not).
Grumphp defaults to run the phpstan check only for files added to index. This behaviour was changed here, all files are checked (respecting exclusion rules).
To revert to grumphp original behaviour edit your grumphp.yml file, so that `use_grumphp_paths` is true 
```yaml
grumphp:
  tasks:
    phpcsfixer: ~
    phpstan:
      use_grumphp_paths: true
```

For some reason (not yet found, the composer run script can't run phpstan with the env var).
The standalone command is then : 
```
_PS_ROOT_DIR_=/path/to/prestashop php ./vendor/bin/phpstan.phar analyse
```

### Header Stamp

[prestashop/header-stamp](https://github.com/PrestaShopCorp/header-stamp/) helps to ensure license headers in files are uptodate.

This command is not triggered automatically, run `composer run license` to launch it.
The licence header is file is located at `dev_src/license_header.txt`.

Notice : the project currently using a Fork for compatibilty with grumphp (symfony/console:^4.0).

## Github Actions

These tools run when you push a commit to a github repository.
Unlike the rest of this package, it relies on Prestashop tools.

The following github actions are ran :
- php ckecks on syntax (syntax errors)
- php cs fixer
- Phpstan
- Php Security Checker ([symfonycorp/security-checker-action](https://github.com/symfonycorp/security-checker-action) : checks composer dependencies with known vulnerabilities.)

## Troubleshooting

If you changed the path to Prestashop in grumphp.yml, you may need to clear the phpstan cache.
Process deleting its cache in php temp dir (`/tmp/` on linux, use `sys_get_temp_dir()` to find it.).

If phpstan retuns something like `Class exampleModule extends unknown class Module.` it means the prestashop dir provided was not found.

## To come

Should come :
- scss/css lint
- js/eslint

## Go further

Lot of information is available at [Prestashop DevDocs](https://devdocs.prestashop.com).

## Legal

_Prestashop_ is a registred tradmark of [Prestashop](https://www.prestashop.com).
