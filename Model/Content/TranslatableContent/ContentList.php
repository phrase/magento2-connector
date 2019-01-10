<?php

namespace Memsource\Connector\Model\Content\TranslatableContent;

use Memsource\Connector\Api\v1\Content\Response\ContentListInterface;

class ContentList implements ContentListInterface
{
    /**
     * @api
     * @var \Memsource\Connector\Model\Content\TranslatableContent\Content[]
     */
    public $items = [];

    /**
     * @api
     * @var int
     */
    public $total;

    /**
     * @api
     * @var int
     */
    public $size;

    /**
     * @api
     * @var int
     */
    public $limit;

    /**
     * @api
     * @var int
     */
    public $offset;

    public function __construct($items, $total, $size, $limit, $offset)
    {
        $this->items = $items;
        $this->total = $total;
        $this->size = $size;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * @return \Memsource\Connector\Model\Content\TranslatableContent\Content[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }
}
