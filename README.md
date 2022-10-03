# Phrase TMS Connector for Magento 2

[![Latest version](https://img.shields.io/packagist/v/memsource/magento2-connector.svg)](https://packagist.org/packages/memsource/magento2-connector)
![Magento 2](https://img.shields.io/badge/Magento-%3E=2.3-brightgreen.svg)
![PHP version](https://img.shields.io/badge/PHP-%3E=5.6.5-blue.svg)

## Phrase TMS

[Phrase TMS](https://www.phrase.com) is the translation management system for global companies wanting to improve 
localization efficiency. 400+ languages, 50+ file types, 25+ MT engines, REST API, and patented AI make Phrase TMS 
the TMS used by many of the world’s leading brands to reduce costs, automate workflows, and optimize the entire 
translation process.

The Phrase TMS extension for Magento 2 allows you to connect your Magento 2 account with Phrase TMS to translate
products, categories, pages, and blocks.


## Installation

There are three ways to install the Phrase TMS Connector extension into your Magento 2 instance:

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

Find the Phrase TMS Connector on the 
[Magento Marketplace](https://marketplace.magento.com/memsource-magento2-connector.html) 
and get the extension. In the Magento Administration, go to `System` → `Web Setup Wizard` → `Extension Manager`. 
Under the number of extensions to install, click on `Review and Install`. Then, find the Phrase TMS Connector, 
and click on the `Install` link. If you are not sure about any of the steps above, check out the 
[Magento Docs](https://docs.magento.com/marketplace/user_guide/buyers/install-extension.html) 
for further information.

## User Guide

If you want to know how to set up and use the Phrase TMS Connector for Magento 2, check out the 
[User Guide](https://github.com/memsource/magento2-connector/raw/master/USER-GUIDE.pdf) for more information.
