Beta version.

# Prestashop Module development setup

Bundled and preconfigured tools to develop a [Prestashop](https://www.prestashop.com/) module.

## Featured local tools

- [php-cs-fixer](https://github.com/FriendsOfPhp/PHP-CS-Fixer)  (configured using prestashop standard)
- [phpstan](https://phpstan.org/) (configured using prestashop standard) 
- [prestashop/header-stamp](https://github.com/PrestaShopCorp/header-stamp/) (update license header in files) (configured using Friends of Presta AFL 3.0 license)
- [prestashop/autoindex](https://github.com/PrestaShopCorp/autoindex) (add missing index.php)

These local tools are used automaticaly by git's precommit hook (php-cs fix & phpstan) or on demande (details below).
 
## Featured CI/github actions

- php syntax check (php 7.2, php 7.3)
- php-cs-fix (configured using prestashop standard)
- phpstan (configured using prestashop standard)
- symfonycorp/security-checker (checks composer packages with security problem)

These tools are triggered automaticaly.

## Under the hood

Before each commit, the lints and checks will run automaticaly, this feature is provided by [phpro/grumphp](https://github.com/phpro/grumphp).

[php-cs-fixer](https://github.com/FriendsOfPhp/PHP-CS-Fixer) and [phpstan](https://phpstan.org/) configuration and bootstraping are provided by [PrestaShop/php-dev-tools/](https://github.com/PrestaShop/php-dev-tools/).

Repository actions are made by [github workflows](https://docs.github.com/en/free-pro-team@latest/actions).
 
This repository is the glue between these elements.
 
You can also run all the checks and lints by running a single command : `composer run check`. (treat files added to commit)
 
# Getting started

2 steps are required.

## 1 - Install via composer

> composer create-project installation is not supported. it was a few days ago. not now.

### Temporary, will building !
> some this package is not yet on packagist. (It will be.)
> so the root composer json (your project) must include :

```json
  "minimum-stability": "dev",

  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/SebSept/fop_example_module",
      "package": {
        "name": "friends-of-presta/examplemodule"
      }
    },
    {
      "type": "vcs",
      "url": "https://github.com/SebSept/autoindex",
      "package": {
        "name": "prestashop/autoindex"
      }
    }
  ],
```

Require the package as a dev dependency :
 ```shell script
 composer require --dev friends-of-presta/examplemodule:dev-main 
 ```
  
 > This is a temporary install, final will be `composer require --dev friends-of-presta/examplemodule` (examplemodule will also change)

## 2 - Configuration

Type `./vendor/bin/fop_module_installer`.

That's all :)

You are ready to go !

# Coding standards

Codes and documents on Friends of Presta targets [_PrestaShop_ ](https://github.com/prestashop/prestashop), so it uses the same coding and writing rules.

For details, you can read [the DevDocs coding standards](https://devdocs.prestashop.com/1.7/development/coding-standards/)

These standards are included in this package.

# Preinstalled tools for local development

These tools are triggered automatically before each commit (except header-stamp).
They can also be launched on demand : `composer run check`.

Commands are registered as composer scripts.
To list commands : `composer run -l`

### Php-cs-fixer

[php-cs-fixer](https://github.com/FriendsOfPhp/PHP-CS-Fixer) fixes your code to follow standards.

This integration follows rules defined by [prestashop/php-dev-tools](https://github.com/prestashop/php-dev-tools).

> This command is triggered by `composer run check`.
> You don't need to trigger it separately.
> However, in case you want to you can, it is possible.

To run only fixer only : 
```
composer run php-cs
```

(Tip: define a command alias `alias cr="composer run"` so you just have to run `cr php-cs`).

### php-stan

[phpstan](https://phpstan.org/) _finds bugs in your code without writing tests._ 

This integration follows rules defines by Prestashop and use a bootstrap file provided by [prestashop/php-dev-tools](https://github.com/prestashop/php-dev-tools).

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
The licence header is file is located at `fop_src/license_header.txt`.

Notice : the project currently using a fork for compatibilty with grumphp (symfony/console:^4.0). (see [composer.json](composer.json) for details)

### Autoindex

It is required for a module to be valid and secure. It requires each folder to have an index.php file to avoid directory listing (in case the webservers allows it).

[prestashop/autoindex](https://github.com/PrestaShopCorp/autoindex) provides this features.

Notice : the project is currently using a fork to work as expected here (see [composer.json](composer.json) for details).
 
# Github Actions

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
