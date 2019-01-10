<?php

namespace Memsource\Connector\Model\Store;

use Memsource\Connector\Api\v1\Store\Response\StoreViewInterface;

class StoreView implements StoreViewInterface
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
     * @var string
     */
    public $language;

    /**
     * @api
     * @var bool
     */
    public $default;

    /**
     * @param int $id
     * @param string $language
     * @param string $name
     * @param bool $default
     */
    public function __construct($id, $name, $language, $default)
    {
        $this->id = $id;
        $this->name = $name;
        $this->language = $language;
        $this->default = $default;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
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
    public function getDefault()
    {
        return $this->default;
    }
}
