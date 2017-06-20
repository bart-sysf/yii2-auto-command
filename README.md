# Yii 2 Auto Command
[![GitHub tag](https://img.shields.io/github/tag/blurrywindows/yii2-auto-command.svg)](https://github.com/blurrywindows/yii2-auto-command)
[![Packagist](https://img.shields.io/packagist/dt/blurrywindows/yii2-auto-command.svg)](https://packagist.org/packages/blurrywindows/yii2-auto-command)
[![Packagist](https://img.shields.io/packagist/l/blurrywindows/yii2-auto-command.svg)](https://packagist.org/packages/blurrywindows/yii2-auto-command)
[![GitHub issues](https://img.shields.io/github/issues/blurrywindows/yii2-auto-command.svg)](https://github.com/blurrywindows/yii2-auto-command/issues)

This is a Yii 2 extension (command) that may be used to automatically generate model classes and apidoc documentation.
The actions in this command should normally not run on a production server. 
The actions mentioned here generate files and data that may be used in development or testing. 
When executing these actions in a production environment, the controller will generate a warning prompt that you may override.

## TABLE OF CONTENTS
* [Features](#features)
* [Requirements](#requirements)
* [Installation](#installation)
* [Configuration](#configuration)
* [Console commands](#console-commands)
* [How to contribute?](#how-to-contribute)
* [Issues](#issues)
* [Read more](#read-more)

## FEATURES
* Generates model classes based on the given database connection.
* Generates model classes that extend a (custom) base class.
* Generates apidoc documentation based on apidoc comment blocks in a custom folder.

## REQUIREMENTS
* PHP >= 5.6.0
* Composer >= 1.1.2 (https://getcomposer.org/)
* Node.js >= 8.1.0 (https://nodejs.org/en/)
* apidoc >= 0.17.6 (```npm install -g apidoc```)

## INSTALLATION
```composer require blurrywindows/yii2-auto-command```

## CONFIGURATION
Add the following lines to your ```console.php``` configuration:
```php
'controllerMap' => [
        'auto' => [
            'class' => 'blurrywindows\AutoCommand\AutoCommand',
            'baseClass' => 'app\models\BaseActiveRecord', // or 'yii\db\ActiveRecord' if you want to use the Yii 2 ActiveRecord as base
            'modelsFolder' => 'models', //Relative to app directory
            'modelsNamespace' => 'app\models',
            'apidocInputFolder' => 'controllers', //Relative to app directory
            'apidocOutputFolder' => 'web/apidoc', //Relative to app directory
            'skipTables' => ['migration'],
        ],
    ],
```

## CONSOLE COMMANDS

### ./yii auto/all
Executes all the actions in the AutoController.

### ./yii auto/gii-models
Generates ActiveRecords for all the tables in your database in your chosen output folder. 
It automatically overwrites the ActiveRecords if they exist.
The ActiveRecords are named ```Base[Tablename]``` and extend your chosen baseClass.
It also creates a class ```[Tablename]``` which extends ```Base[Tablename]``` for custom code, extra validation rules, etc.
The ```[Tablename]``` class will not be overridden when executing the action again.

### ./yii auto/apidoc
Generates API documentation based on [apidoc](http://apidocjs.com/) from the comments in your chosen input folder.
It outputs the documentation in your chosen output folder.
You may include this folder in Git if you want to export the documentation to a production server.
Please note, apidoc is a dev-dependency in Node.js. It will only be installed when using the ```npm install``` command.

## HOW TO CONTRIBUTE
You may contribute in any way you want, but please contact me beforehand to prevent merge-conflicts by [creating an issue](https://github.com/blurrywindows/yii2-auto-command/issues).

## ISSUES
If you have any questions or experience any issues with this command, please [submit an issue](https://github.com/blurrywindows/yii2-auto-command/issues).

## READ MORE
* https://github.com/yiisoft/yii2/blob/master/README.md
* https://github.com/yiisoft/yii2/tree/master/docs/guide