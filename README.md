# Memsource Connector for Magento 2

[![Latest version](https://img.shields.io/packagist/v/memsource/magento2-connector.svg)](https://packagist.org/packages/memsource/magento2-connector)
![Magento 2](https://img.shields.io/badge/Magento-%3E=2.1-brightgreen.svg)
![PHP version](https://img.shields.io/badge/PHP-%3E=5.6.5-blue.svg)

## Memsource

Memsource is a cloud-based translation platform trusted by reputable companies and agencies 
around the world since its inception in 2010.

This extension allows you to connect Magento 2 with Memsource Cloud and translate the following
content: products, categories, pages and blocks.

## Installation

There are three ways how to install Memsource Connector extension into your Magento 2 instance.

### 1. With Composer

Install the extension via [Composer](https://getcomposer.org):

```bash
composer require memsource/magento2-connector
php bin/magento module:enable Memsource_Connector
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```

### 2. Without Composer

In case you can't use Composer:

1. Download zip file of this extension
2. Extract downloaded zip into the folder `app/code/Memsource/Connector` in your Magento 2 installation
3. Enable extension and clear cache:

```bash
php bin/magento module:enable Memsource_Connector
php bin/magento setup:upgrade
php bin/magento cache:flush
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
```

### 3. Magento Marketplace

Our extension is currently under review, so it will be available within a few weeks.

## Configuration

In Magento 2 Administration go to `Stores` → `Configuration` → `MEMSOURCE`. 
To connect Magento with Memsource you will need URL and Token: 

<p align="center">
<img src="https://github.com/memsource/magento2-connector/raw/master/docs/magento-config.png">
</p>

Then open Memsource Cloud and go to `Setup` → `Integrations` → `Connectors` → `New`. Fill in form with URL 
and Token from previous step and click on Test connection. Then choose source website, store and store view:

<p align="center">
<img src="https://github.com/memsource/magento2-connector/raw/master/docs/memsource-config.png">
</p>
