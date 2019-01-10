<?php

namespace Memsource\Connector\Api\v1\Content\Response;

interface ContentTypesInterface
{
    /**
     * @return \Memsource\Connector\Api\v1\Content\Response\ContentTypeInterface[]
     */
    public function getTypes();
}
