<?php

namespace Memsource\Connector\Model\Content\TranslatableContent;

use Memsource\Connector\Api\v1\Content\Response\ContentDetailInterface;

class ContentDetail implements ContentDetailInterface
{
    /**
     * @api
     * @var \Memsource\Connector\Model\Content\TranslatableContent\Content
     */
    public $content = [];

    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * @return \Memsource\Connector\Model\Content\TranslatableContent\Content
     */
    public function getContent()
    {
        return $this->content;
    }
}
