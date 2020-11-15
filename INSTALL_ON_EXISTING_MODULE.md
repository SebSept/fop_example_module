# Installing the Fop Module Tools on an existing module.

Require the package as a dev dependency :
 ```shell script
 composer require --dev friends-of-presta/examplemodule --repository "{\"type\": \"vcs\", \"url\": \"https://github.com/SebSept/fop_example_module\"}" --stability=dev
 ```
  
 > This is a temporary install, final will be `composer require --dev friends-of-presta/examplemodule` 

Then launch `./vendor/bin/fop_module_installer`.
