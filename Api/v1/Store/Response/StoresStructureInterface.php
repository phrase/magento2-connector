<?php

namespace Memsource\Connector\Api\v1\Store\Response;

interface StoresStructureInterface
{
    /**
     * @return \Memsource\Connector\Api\v1\Store\Response\WebsiteInterface[]
     */
    public function getWebsites();
}
