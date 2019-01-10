<?php

namespace Memsource\Connector\Model\Content\ContentTypes;

use Memsource\Connector\Model\Content\TranslatableContent\BlockRepository;
use Memsource\Connector\Model\Content\TranslatableContent\CategoryRepository;
use Memsource\Connector\Model\Content\TranslatableContent\PageRepository;
use Memsource\Connector\Model\Content\TranslatableContent\ProductRepository;

class ContentTypesFactory
{
    public function create()
    {
        $contentTypes = new ContentTypes;

        foreach ($this->getSupportedContentTypes() as $supportedContentType) {
            $contentTypes->addContentType(new ContentType($supportedContentType, ucfirst($supportedContentType)));
        }

        return $contentTypes;
    }

    private function getSupportedContentTypes()
    {
        return [
            ProductRepository::CONTENT_TYPE,
            CategoryRepository::CONTENT_TYPE,
            BlockRepository::CONTENT_TYPE,
            PageRepository::CONTENT_TYPE
        ];
    }
}
