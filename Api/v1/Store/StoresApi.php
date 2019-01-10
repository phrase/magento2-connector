<?php

namespace Memsource\Connector\Api\v1\Store;

use Magento\Store\Model\System\Store;
use Memsource\Connector\Model\Store\StoresStructureFactory;
use Memsource\Connector\Model\Validator\RequestValidator;

class StoresApi
{
    /** @var RequestValidator */
    private $requestValidator;

    /** @var StoresStructureFactory */
    private $storesStructureFactory;

    /** @var Store */
    private $storeModel;

    public function __construct(
        RequestValidator $requestValidator,
        StoresStructureFactory $storesStructureFactory,
        Store $storeModel
    ) {
        $this->requestValidator = $requestValidator;
        $this->storesStructureFactory = $storesStructureFactory;
        $this->storeModel = $storeModel;
    }

    /**
     * Get hierarchy of websites, stores and store views with their language codes.
     * @param string $token
     * @return \Memsource\Connector\Api\v1\Store\Response\StoresStructureInterface
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function getStores($token)
    {
        $this->requestValidator->validateToken($token);
        $storesStructure = $this->storeModel->getStoresStructure();

        return $this->storesStructureFactory->create($storesStructure);
    }
}
