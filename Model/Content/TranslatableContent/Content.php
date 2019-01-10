<?php

namespace Memsource\Connector\Model\Content\TranslatableContent;

use Memsource\Connector\Api\v1\Content\Response\ContentInterface;

class Content implements ContentInterface
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
     * @var int
     */
    public $modified;

    /**
     * @api
     * @var string
     */
    public $body;

    /**
     * @api
     * @var bool
     */
    public $folder = false;

    /**
     * @param $id
     * @param $name
     * @param $modified
     * @param string|null $body
     */
    public function __construct($id, $name, $modified, $body = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->body = $body;
        $timestamp = strtotime($modified);

        if ($timestamp !== false) {
            $this->modified = $timestamp;
        } else {
            $this->modified = time();
        }
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
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return int
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * @return bool
     */
    public function getFolder()
    {
        return $this->folder;
    }
}
