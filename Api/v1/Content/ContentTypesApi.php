<?php

namespace Memsource\Connector\Api\v1\Content;

use Memsource\Connector\Model\Content\ContentTypes\ContentTypesFactory;
use Memsource\Connector\Model\Validator\RequestValidator;

class ContentTypesApi
{
    /** @var RequestValidator */
    private $requestValidator;

    /** @var ContentTypesFactory */
    private $contentTypesFactory;

    public function __construct(
        RequestValidator $requestValidator,
        ContentTypesFactory $contentTypesFactory
    ) {
        $this->requestValidator = $requestValidator;
        $this->contentTypesFactory = $contentTypesFactory;
    }

    /**
     * Get list of content types.
     * @param string $token
     * @return \Memsource\Connector\Api\v1\Content\Response\ContentTypesInterface
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function getContentTypes($token)
    {
        $this->requestValidator->validateToken($token);

        return $this->contentTypesFactory->create();
    }
}
