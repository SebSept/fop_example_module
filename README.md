# About

This repository serve as a start point for a building a quality Prestashop module.
It's pre-configured with tools to ensure that the code complies with the standards and is valid. 

It uses pre-commit validation for local development and github actions for repository checks.

It relies on prestashop tools. Prestashop tools relies on classic tools (php-cs-fix, phpstan and more (to come)).

Local actions are _bundled_ using [phpro/grumphp](https://github.com/phpro/grumphp).
Repository actions are made by [github workflows](https://docs.github.com/en/free-pro-team@latest/actions).
 
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

## Preinstalled tools for local development

These tools are used when writing code.
They are triggered automatically before each commit (except header-stamp).
They can also be launched on demand (`composer run check` or `./bin/vendor/grumphp run`)

Commands are registered as composer scripts.
To list commands : `composer run -l`

### Php-cs-fixer

[php-cs-fixer](https://github.com/FriendsOfPhp/PHP-CS-Fixer) fixes your code to follow standards.
It follows rules defined by [prestashop/php-dev-tools](https://github.com/prestashop/php-dev-tools).

To run only fixer only : `composer run php-cs`.

(Tip: define a command alias `alias cr="composer run"` so you just have to run `cr php-cs`).

### php-stan

[phpstan](https://phpstan.org/) _finds bugs in your code without writing tests._ It follows rules defines by Prestashop and use a bootstrap file provided by [prestashop/php-dev-tools](https://github.com/prestashop/php-dev-tools).

In case you want to run phpstan only, you must edit composer.json and replace `/path/to/prestashop/` by a real local path to prestashop directory.

```json
{
  "scripts": {
    "phpstan": [
      "@putenv _PS_ROOT_DIR_=/path/to/prestashop/",
      "phpstan analyse ./"
    ]}
}
```

Then you are ready to run `composer run phpstan`

### Header Stamp

[prestashop/header-stamp](https://github.com/PrestaShopCorp/header-stamp/) helps to ensure license headers in files are uptodate.

This command is not triggered automatically, run `composer run license` to launch it.
The licence header is file is located at `dev_src/license_header.txt`.

Notice : the project currently using a Fork for compatibilty with grumphp (symfony/console:^4.0).

## Github Actions

These tools run when you push a commit to a github repository.
Unlike the rest of this package, it relies on Prestashop tools.

### Php syntax

@todo 

### Php cs fixer

@todo

### Phpstan

@todo

### Php Security Checker

[symfonycorp/security-checker-action](https://github.com/symfonycorp/security-checker-action) provided by Symfony.
Checks composer dependencies with known vulnerabilities.

## Troubleshooting

If you changed the path to Prestashop in grumphp.yml, you may need to clear the phpstan cache.
Process deleting its cache in php temp dir (`/tmp/` on linux, use `sys_get_temp_dir()` to find it.).

## Go further

Lot of information is available at [Prestashop DevDocs](https://devdocs.prestashop.com).

## Legal

_Prestashop_ is a registred tradmark of [Prestashop](https://www.prestashop.com).
