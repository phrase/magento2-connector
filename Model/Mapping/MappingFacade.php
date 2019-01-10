<?php

namespace Memsource\Connector\Model\Mapping;

use Magento\Framework\ObjectManagerInterface;
use Memsource\Connector\Model\ResourceModel\Mapping as MappingResource;
use Memsource\Connector\Model\ResourceModel\Mapping\Collection as MappingCollection;

final class MappingFacade
{
    /** @var MappingCollection */
    private $mappingCollection;

    /** @var ObjectManagerInterface */
    private $objectManager;

    public function __construct(
        MappingCollection $collection,
        ObjectManagerInterface $objectManager
    ) {
    
        $this->mappingCollection = $collection;
        $this->objectManager = $objectManager;
    }

    /**
     * Save mapping.
     * @param $sourceId int
     * @param $targetId int
     * @param $type string
     * @param $lang string
     * @return Mapping
     */
    public function add($sourceId, $targetId, $type, $lang)
    {
        $mapping = $this->mappingCollection->getOneBySourceIdAndTypeAndLang($sourceId, Mapping::BLOCK_TYPE, $lang);

        if ($mapping === false) {
            $mapping = $this->getMapping();
            $mapping->setSourceId($sourceId);
            $mapping->setTargetId($targetId);
            $mapping->setType($type);
            $mapping->setLang($lang);
            $this->getMappingResource()->save($mapping);
        }

        return $mapping;
    }

    /**
     * @return Mapping
     */
    private function getMapping()
    {
        return $this->objectManager->create(Mapping::class);
    }

    /**
     * @return MappingResource
     */
    private function getMappingResource()
    {
        return $this->objectManager->create(MappingResource::class);
    }
}
