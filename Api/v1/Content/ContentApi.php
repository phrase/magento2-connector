<?php

namespace Memsource\Connector\Api\v1\Content;

use Memsource\Connector\Model\Content\TranslatableContent\BlockRepository;
use Memsource\Connector\Model\Content\TranslatableContent\CategoryRepository;
use Memsource\Connector\Model\Content\TranslatableContent\ContentRepositoryInterface;
use Memsource\Connector\Model\Content\TranslatableContent\PageRepository;
use Memsource\Connector\Model\Content\TranslatableContent\ProductAttributeRepository;
use Memsource\Connector\Model\Content\TranslatableContent\ProductRepository;
use Memsource\Connector\Model\Validator\RequestValidator;

class ContentApi
{
    const DEFAULT_LIMIT = 5000;
    const DEFAULT_OFFSET = 0;

    /** @var RequestValidator */
    private $requestValidator;

    /** @var ContentRepositoryInterface[] */
    private $repositories = [];

    public function __construct(
        RequestValidator $requestValidator,
        BlockRepository $blockRepository,
        CategoryRepository $categoryRepository,
        PageRepository $pageRepository,
        ProductRepository $productRepository,
        ProductAttributeRepository $productAttributeRepository
    ) {
        $this->requestValidator = $requestValidator;
        $this->repositories = [
            BlockRepository::CONTENT_TYPE => $blockRepository,
            CategoryRepository::CONTENT_TYPE => $categoryRepository,
            PageRepository::CONTENT_TYPE => $pageRepository,
            ProductRepository::CONTENT_TYPE => $productRepository,
            ProductAttributeRepository::CONTENT_TYPE => $productAttributeRepository,
        ];
    }

    /**
     * Get content filtered by given content-type.
     * @param string $contentType
     * @param int $storeId
     * @param string $token
     * @param int $limit
     * @param int $offset
     * @return \Memsource\Connector\Api\v1\Content\Response\ContentListInterface
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function getContentList($contentType, $storeId, $token, $limit = self::DEFAULT_LIMIT, $offset = self::DEFAULT_OFFSET)
    {
        $this->requestValidator->validateToken($token);
        $this->requestValidator->validateContentType($contentType, array_keys($this->repositories));
        $this->requestValidator->validateStoreId($storeId);

        return $this->repositories[$contentType]->getList($storeId, $limit, $offset);
    }

    /**
     * Get content by ID.
     * @param string $contentType
     * @param int $id
     * @param int $storeId
     * @param string $token
     * @return \Memsource\Connector\Api\v1\Content\Response\ContentDetailInterface
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function getSingleContent($contentType, $id, $storeId, $token)
    {
        $this->requestValidator->validateToken($token);
        $this->requestValidator->validateContentType($contentType, array_keys($this->repositories));
        $this->requestValidator->validateStoreId($storeId);

        return $this->repositories[$contentType]->getOne($id, $storeId);
    }

    /**
     * @param string $contentType
     * @param int $id
     * @param int $storeId
     * @param string $token
     * @param string $body
     * @return \Memsource\Connector\Api\v1\Content\Response\ContentDetailInterface
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function updateContent($contentType, $id, $storeId, $token, $body)
    {
        $this->requestValidator->validateToken($token);
        $this->requestValidator->validateContentType($contentType, array_keys($this->repositories));
        $this->requestValidator->validateStoreId($storeId);

        return $this->repositories[$contentType]->update($storeId, $id, $body);
    }
}
