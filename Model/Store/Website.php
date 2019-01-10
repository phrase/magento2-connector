<?php

namespace Memsource\Connector\Model\Store;

use Memsource\Connector\Api\v1\Store\Response\WebsiteInterface;

class Website implements WebsiteInterface
{
    /**
     * @api
     * @var int
     */
    public $id;

    /**
     * @api
     * @var string
     */
    public $name;

    /**
     * @var \Memsource\Connector\Model\Store\Store[]
     */
    public $stores;

    /**
     * @param int $id
     * @param string $name
     * @param \Memsource\Connector\Model\Store\Store[] $stores
     */
    public function __construct($id, $name, $stores)
    {
        $this->id = $id;
        $this->name = $name;
        $this->stores = $stores;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return \Memsource\Connector\Model\Store\Store[]
     */
    public function getStores()
    {
        return $this->stores;
    }
}
