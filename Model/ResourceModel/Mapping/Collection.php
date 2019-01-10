<?php

namespace Memsource\Connector\Model\ResourceModel\Mapping;

use Magento\Framework\DataObject;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Memsource\Connector\Model\Mapping\Mapping;

class Collection extends AbstractCollection
{
    /** @var string */
    protected $_idFieldName = 'map_id';
    protected $_eventPrefix = 'memsource_connector_translation_mapping';
    protected $_eventObject = 'translation_mapping_collection';

    protected function _construct()
    {
        $this->_init(Mapping::class, \Memsource\Connector\Model\ResourceModel\Mapping::class);
    }

    /**
     * Get one by source id and type and lang.
     * @param $sourceId int
     * @param $type string
     * @param $lang string
     * @return DataObject|Mapping|false
     */
    public function getOneBySourceIdAndTypeAndLang($sourceId, $type, $lang)
    {
        $this->addFilter(Mapping::COLUMN_SOURCE_ID, $sourceId);
        $this->addFilter(Mapping::COLUMN_TYPE, $type);
        $this->addFilter(Mapping::COLUMN_LANG, $lang);

        $mapping = $this->getFirstItem();

        if (!$mapping->getData()) {
            return false;
        }

        return $mapping;
    }
}
