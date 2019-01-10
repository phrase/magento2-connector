<?php

namespace Memsource\Connector\Api\v1\Store\Response;

interface WebsiteInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return \Memsource\Connector\Api\v1\Store\Response\StoreInterface[]
     */
    public function getStores();
}
