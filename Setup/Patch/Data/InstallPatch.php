<?php declare(strict_types=1);

namespace Memsource\Connector\Setup\Patch\Data;

use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Model\Store;
use Memsource\Connector\Block\Adminhtml\Config\GenerateTokenButton;
use Memsource\Connector\Helper\Configuration;

/**
* Patch is mechanism, that allows to do atomic upgrade data changes
*/
class InstallPatch implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var Configuration
     */
    private $configuration;
    /**
     * @var ConfigInterface
     */
    private $resourceConfig;
    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @param  ModuleDataSetupInterface  $moduleDataSetup
     * @param  Configuration  $configuration
     * @param  ConfigInterface  $resourceConfig
     * @param  CacheInterface  $cache
     */

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        Configuration $configuration,
        ConfigInterface $resourceConfig,
        CacheInterface $cache
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->configuration = $configuration;
        $this->resourceConfig = $resourceConfig;
        $this->cache = $cache;
    }

    /**
     * Do Upgrade
     *
     * @return void
     */
    public function apply(): void
    {
        $this->moduleDataSetup->startSetup();
        $this->disableDebugMode();
        $this->generateToken();
        $this->cache->clean(['config']);
        $this->moduleDataSetup->endSetup();
    }

    /**
     * @inheritdoc
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies(): array
    {
        return [];
    }

    private function disableDebugMode(): void
    {
        $this->resourceConfig->saveConfig(
            $this->configuration->getDebugPath(),
            0,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            Store::DEFAULT_STORE_ID
        );
    }

    private function generateToken(): void
    {
        $this->resourceConfig->saveConfig(
            $this->configuration->getTokenPath(),
            GenerateTokenButton::generateToken(),
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            Store::DEFAULT_STORE_ID
        );
    }
}
