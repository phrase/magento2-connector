<?php

namespace Memsource\Connector\Model\Content\ContentTypes;

use Memsource\Connector\Api\v1\Content\Response\ContentTypesInterface;

class ContentTypes implements ContentTypesInterface
{
    /**
     * @api
     * @var \Memsource\Connector\Model\Content\ContentTypes\ContentType[]
     */
    public $types;

    /**
     * @param \Memsource\Connector\Model\Content\ContentTypes\ContentType[] $types
     */
    public function __construct($types = [])
    {
        $this->types = $types;
    }

    /**
     * @return \Memsource\Connector\Model\Content\ContentTypes\ContentType[]
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @param \Memsource\Connector\Model\Content\ContentTypes\ContentType $type
     */
    public function addContentType($type)
    {
        $this->types[] = $type;
    }
}
