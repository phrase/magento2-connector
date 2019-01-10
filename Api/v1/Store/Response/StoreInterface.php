<?php

namespace Memsource\Connector\Api\v1\Store\Response;

interface StoreInterface
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
     * @return \Memsource\Connector\Api\v1\Store\Response\StoreViewInterface[]
     */
    public function getViews();
}
