<?php

namespace Memsource\Connector\Model\Content\ContentTypes;

use Memsource\Connector\Api\v1\Content\Response\ContentTypeInterface;

class ContentType implements ContentTypeInterface
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
     * @var bool
     */
    public $folder = true;

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return string
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
     * @return bool
     */
    public function getFolder()
    {
        return $this->folder;
    }
}
