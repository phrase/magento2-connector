# Memsource Connector for Magento 2

[![Latest version](https://img.shields.io/packagist/v/memsource/magento2-connector.svg)](https://packagist.org/packages/memsource/magento2-connector)
![Magento 2](https://img.shields.io/badge/Magento-%3E=2.1-brightgreen.svg)
![PHP version](https://img.shields.io/badge/PHP-%3E=5.6.5-blue.svg)

## Memsource

[Memsource](https://www.memsource.com) is the translation management system for global companies wanting to improve 
localization efficiency. 400+ languages, 50+ file types, 25+ MT engines, REST API, and patented AI make Memsource 
the TMS used by many of the world’s leading brands to reduce costs, automate workflows, and optimize the entire 
translation process.

The Memsource extension for Magento 2 allows you to connect your Memsource 2 account with Memsource to translate
products, categories, pages, and blocks.


## Installation

There are three ways to install the Memsource Connector extension into your Magento 2 instance:

### 1. With Composer

Install the extension via [Composer](https://getcomposer.org):

```bash
composer require memsource/magento2-connector
php bin/magento module:enable Memsource_Connector
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```

### 2. Without Composer

If you are unable to use Composer:

1. Download the zip file above
2. In your Magento 2 installation, extract the downloaded zip file into the folder `app/code/Memsource/Connector`
3. Enable the extension and clear the cache:

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

In Magento 2, go to `Stores` → `Configuration` → `MEMSOURCE`.
Magento with Memsource, you will need a URL and a Token:

<p align="center">
<img src="https://github.com/memsource/magento2-connector/raw/master/docs/magento-config.png">
</p>

Then, open Memsource and go to `Setup` → `Integrations` → `Connectors` → `New`. Fill in the form with your 
URL and Token from the previous step and click on `Test connection`. Then, choose the source website, store, 
and store view:

<p align="center">
<img src="https://github.com/memsource/magento2-connector/raw/master/docs/memsource-config.png">
</p>
