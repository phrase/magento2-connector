<?php

namespace Memsource\Connector\Model\Store;

use Memsource\Connector\Api\v1\Store\Response\StoresStructureInterface;

class StoresStructure implements StoresStructureInterface
{
    /**
     * @api
     * @var \Memsource\Connector\Model\Store\Website[]
     */
    public $websites;

    /**
     * @param \Memsource\Connector\Model\Store\Website[] $website
     */
    public function __construct($website)
    {
        $this->websites = $website;
    }

    /**
     * @return \Memsource\Connector\Model\Store\Website[]
     */
    public function getWebsites()
    {
        return $this->websites;
    }
}
