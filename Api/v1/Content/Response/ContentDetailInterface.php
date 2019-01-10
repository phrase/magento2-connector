<?php

namespace Memsource\Connector\Api\v1\Content\Response;

interface ContentDetailInterface
{
    /**
     * @return \Memsource\Connector\Api\v1\Content\Response\ContentInterface
     */
    public function getContent();
}
