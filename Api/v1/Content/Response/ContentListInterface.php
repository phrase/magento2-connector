<?php

namespace Memsource\Connector\Api\v1\Content\Response;

interface ContentListInterface
{
    /**
     * @return int
     */
    public function getTotal();

    /**
     * @return int
     */
    public function getSize();

    /**
     * @return int
     */
    public function getLimit();

    /**
     * @return int
     */
    public function getOffset();

    /**
     * @return \Memsource\Connector\Api\v1\Content\Response\ContentInterface[]
     */
    public function getItems();
}
