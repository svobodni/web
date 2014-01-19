# TranslatorModule module for Venne:Framework and Nette Framework

## Installation in Nette

run:

	php composer.phar require venne/translator-module:2.0.x

add to bootstrap.php

	TranslatorModule\DI\TranslatorExtension::register($configurator);

## Installation in Venne

run only:

	php composer.phar require venne/translator-module:2.0.x

## How to use

### Configuration

add to `config.neon` new directories. For example:

	translator:
		dictionaries:
			- %modules.cms.path%/Resources/translations
			- %modules.blog.path%/Resources/translations

### Dictionary

Dictionary is folder with translation files. File format must be `<name>.<lang>.<driver>`.

#### Examples
- admin.cs.latte
- blog.pl.php
- eshop.de.ini

## Extraction of strings

command:

	php www/index.php translator:extract vendor/venne/cms-module/

save into file:

	php www/index.php translator:extract vendor/venne/cms-module/ vendor/venne/cms-module/CmsModule/Resources/translations/test.cs.neon