<?php

namespace Memsource\Connector\Model\Content\ContentTypes;

use Memsource\Connector\Model\Content\TranslatableContent\BlockRepository;
use Memsource\Connector\Model\Content\TranslatableContent\CategoryRepository;
use Memsource\Connector\Model\Content\TranslatableContent\PageRepository;
use Memsource\Connector\Model\Content\TranslatableContent\ProductAttributeRepository;
use Memsource\Connector\Model\Content\TranslatableContent\ProductRepository;

class ContentTypesFactory
{
    public function create()
    {
        $contentTypes = new ContentTypes;

        foreach ($this->getSupportedContentTypes() as $supportedContentType) {
            $name = ucwords($supportedContentType);
            $name = str_replace('_', ' ', $name);
            $contentTypes->addContentType(new ContentType($supportedContentType, $name));
        }

        return $contentTypes;
    }

    private function getSupportedContentTypes()
    {
        return [
            ProductRepository::CONTENT_TYPE,
            ProductAttributeRepository::CONTENT_TYPE,
            CategoryRepository::CONTENT_TYPE,
            BlockRepository::CONTENT_TYPE,
            PageRepository::CONTENT_TYPE
        ];
    }
}
