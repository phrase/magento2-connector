<?php

namespace Memsource\Connector\Setup;

use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Store\Model\Store;
use Memsource\Connector\Block\Adminhtml\Config\GenerateTokenButton;
use Memsource\Connector\Helper\Configuration;

class InstallData implements InstallDataInterface
{
    /** @var CacheInterface */
    private $cache;

    /** @var Configuration */
    private $configuration;

    /** @var ConfigInterface */
    private $resourceConfig;

    public function __construct(
        Configuration $configuration,
        ConfigInterface $resourceConfig,
        CacheInterface $cache
    ) {
        $this->configuration = $configuration;
        $this->resourceConfig = $resourceConfig;
        $this->cache = $cache;
    }

    /**
     * @inheritdoc
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $this->disableDebugMode();
        $this->generateToken();
        $this->cache->clean(['config']);
        $setup->endSetup();
    }

    private function disableDebugMode()
    {
        $this->resourceConfig->saveConfig(
            $this->configuration->getDebugPath(),
            0,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            Store::DEFAULT_STORE_ID
        );
    }

    private function generateToken()
    {
        $this->resourceConfig->saveConfig(
            $this->configuration->getTokenPath(),
            GenerateTokenButton::generateToken(),
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            Store::DEFAULT_STORE_ID
        );
    }
}
