<?php

namespace Memsource\Connector\Model\Store;

use Memsource\Connector\Api\v1\Store\Response\StoreInterface;

class Store implements StoreInterface
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
     * @api
     * @var \Memsource\Connector\Model\Store\StoreView[]
     */
    public $views;

    public function __construct($id, $name, $views)
    {
        $this->id = $id;
        $this->name = $name;
        $this->views = $views;
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
     * @return \Memsource\Connector\Model\Store\StoreView[]
     */
    public function getViews()
    {
        return $this->views;
    }
}
