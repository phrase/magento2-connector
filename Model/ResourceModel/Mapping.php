<?php

namespace Memsource\Connector\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Mapping extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('memsource_connector_translation_mapping', 'map_id');
    }
}
