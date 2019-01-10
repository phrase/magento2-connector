<?php

namespace Memsource\Connector\Model\Mapping;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class Mapping extends AbstractModel implements IdentityInterface
{
    /** @var string */
    const CACHE_TAG = 'memsource_connector_translation_mapping';

    /** @var string */
    const BLOCK_TYPE = 'block';
    const PAGE_TYPE = 'page';

    /** @var string */
    const COLUMN_SOURCE_ID = 'map_source_id';
    const COLUMN_TARGET_ID = 'map_target_id';
    const COLUMN_TYPE = 'map_type';
    const COLUMN_LANG = 'map_lang';

    /** @var string */
    protected $_cacheTag = self::CACHE_TAG;
    protected $_eventPrefix = self::CACHE_TAG;

    protected function _construct()
    {
        $this->_init(\Memsource\Connector\Model\ResourceModel\Mapping::class);
    }

    /**
     * @inheritdoc
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Set source id.
     * @param $id int
     * @return self
     */
    public function setSourceId($id)
    {
        $this->setData(self::COLUMN_SOURCE_ID, $id);
        return $this;
    }

    /**
     * Get source id.
     * @return int|null
     */
    public function getSourceId()
    {
        return $this->getData(self::COLUMN_SOURCE_ID);
    }

    /**
     * Set target id.
     * @param $id int
     * @return self
     */
    public function setTargetId($id)
    {
        $this->setData(self::COLUMN_TARGET_ID, $id);
        return $this;
    }

    /**
     * Get target id.
     * @return int|null
     */
    public function getTargetId()
    {
        return $this->getData(self::COLUMN_TARGET_ID);
    }

    /**
     * Set type.
     * @param $type string
     * @return self
     */
    public function setType($type)
    {
        $this->setData(self::COLUMN_TYPE, $type);
        return $this;
    }

    /**
     * Get type.
     * @return string|null
     */
    public function getType()
    {
        return $this->getData(self::COLUMN_TYPE);
    }

    /**
     * Set lang.
     * @param $lang string
     * @return self
     */
    public function setLang($lang)
    {
        $this->setData(self::COLUMN_LANG, $lang);
        return $this;
    }

    /**
     * Get lang.
     * @return string|null
     */
    public function getLang()
    {
        return $this->getData(self::COLUMN_LANG);
    }
}
