<?php

namespace Memsource\Connector\Model\Store;

use Magento\Config\Model\Config\Backend\Admin\Custom;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManager;
use Magento\Store\Model\Store\Interceptor;

class StoresStructureFactory
{
    /** @var StoreManager */
    private $storeManager;

    public function __construct(StoreManager $storeManager)
    {
        $this->storeManager = $storeManager;
    }

    /**
     * @param array $storesStructure
     * @return StoresStructure
     */
    public function create($storesStructure)
    {
        $websites = [];

        foreach ($storesStructure as $websiteStructure) {
            $stores = [];

            foreach ($websiteStructure['children'] as $storeStructure) {
                $storeViews = [];

                foreach ($storeStructure['children'] as $storeViewStructure) {
                    $storeEntity = $this->storeManager->getStore($storeViewStructure['value']);
                    $storeViews[] = $this->createStoreView($storeEntity);
                }

                $stores[] = new Store($storeStructure['value'], $storeStructure['label'], $storeViews);
            }

            $websites[] = new Website($websiteStructure['value'], $websiteStructure['label'], $stores);
        }
            
        return new StoresStructure($websites);
    }

    /**
     * @param Interceptor|StoreInterface $store
     * @return StoreView
     */
    public function createStoreView($store)
    {
        $language = $store->getConfig(Custom::XML_PATH_GENERAL_LOCALE_CODE);
        $languageCode = mb_strtolower($language);

        return new StoreView($store->getId(), $store->getName(), $languageCode, $store->isDefault());
    }
}
