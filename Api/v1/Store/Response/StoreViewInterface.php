<?php

namespace Memsource\Connector\Api\v1\Store\Response;

interface StoreViewInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getLanguage();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return bool
     */
    public function getDefault();
}
