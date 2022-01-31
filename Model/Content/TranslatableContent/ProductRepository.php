<?php

namespace Memsource\Connector\Model\Content\TranslatableContent;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\ProductRepository as MagentoProductRepository;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DB\Select;
use Magento\Store\Model\StoreManagerInterface;

class ProductRepository extends AbstractContentRepository
{
    const CONTENT_TYPE = 'products';

    const ATTRIBUTE_ID = 'id';
    const ATTRIBUTE_NAME = 'name';
    const ATTRIBUTE_DESCRIPTION = 'description';
    const ATTRIBUTE_SHORT_DESCRIPTION = 'short_description';
    const ATTRIBUTE_META_TITLE = 'meta_title';
    const ATTRIBUTE_META_KEYWORD = 'meta_keyword';
    const ATTRIBUTE_META_DESCRIPTION = 'meta_description';

    /** @var StoreManagerInterface */
    protected $storeManager;

    /** @var CollectionFactory */
    protected $productCollectionFactory;

    /** @var MagentoProductRepository */
    protected $productRepository;

    public function __construct(
        StoreManagerInterface $storeManager,
        CollectionFactory $productCollectionFactory,
        MagentoProductRepository $productRepository,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->storeManager = $storeManager;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productRepository = $productRepository;
        parent::__construct($scopeConfig);
    }

    /**
     * @inheritdoc
     */
    public function update($storeId, $contentId, $serializedBody)
    {
        $sourceProduct = $this->findItemById($contentId, $storeId);
        $translatedProduct = $this->deserializeItem($serializedBody, $sourceProduct);
        $this->storeManager->setCurrentStore($storeId);
        $this->productRepository->save($translatedProduct);

        return $this->getOne($contentId, $storeId);
    }

    /**
     * @inheritdoc
     */
    protected function findTotalItemsCount($storeId)
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addStoreFilter($storeId);

        return $collection->getSize();
    }

    /**
     * @param int $storeId
     * @param int $limit
     * @param int $offset
     * @return ProductInterface[]
     */
    protected function findItems($storeId, $limit, $offset)
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addStoreFilter($storeId);
        $collection->addAttributeToFilter(self::ATTRIBUTE_NAME, ['notnull' => true]);
        $collection->setOrder(self::ATTRIBUTE_ID, Select::SQL_ASC);
        $collection->getSelect()->limit($limit, $offset);

        return $collection->getItems() ?: [];
    }

    /**
     * @param int $productId
     * @param int $storeId
     * @return ProductInterface
     */
    protected function findItemById($productId, $storeId)
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addStoreFilter($storeId);
        $collection->addAttributeToSelect($this->getTranslatableAttributes());

        return $collection->getItemById($productId);
    }

    /**
     * @param ProductInterface $product
     * @param int $storeId
     * @param string|null $serializedBody
     * @return Content
     */
    protected function createContent($product, $storeId, $serializedBody = null)
    {
        return new Content($product->getId(), $product->getName(), $product->getUpdatedAt(), $serializedBody);
    }

    /**
     * @param ProductInterface $item
     * @param string $attributeName
     * @return string
     */
    protected function getAttributeValue($item, $attributeName)
    {
        $attribute = $item->getCustomAttribute($attributeName);

        if ($attribute !== null) {
            return $attribute->getValue();
        }

        return '';
    }

    /**
     * @param array $deserialized
     * @param ProductInterface $item
     * @return ProductInterface
     */
    protected function createItemFromArray($deserialized, $item)
    {
        $item->setName($deserialized[$this->getMainTranslatableAttribute()]);
        unset($deserialized[$this->getMainTranslatableAttribute()]);

        foreach ($deserialized as $attributeName => $attributeValue) {
            $attributeValue = $this->decodeWidgets($attributeValue);
            if ($item->getCustomAttribute($attributeName) !== null) {
                $item->getCustomAttribute($attributeName)->setValue($attributeValue);
            } else {
                $item->setCustomAttribute($attributeName, $attributeValue);
            }
        }

        return $item;
    }

    /**
     * @inheritdoc
     */
    protected function getMainTranslatableAttribute()
    {
        return self::ATTRIBUTE_NAME;
    }

    /**
     * @inheritdoc
     */
    protected function getTranslatableAttributes()
    {
        return [
            self::ATTRIBUTE_NAME,
            self::ATTRIBUTE_DESCRIPTION,
            self::ATTRIBUTE_SHORT_DESCRIPTION,
            self::ATTRIBUTE_META_TITLE,
            self::ATTRIBUTE_META_KEYWORD,
            self::ATTRIBUTE_META_DESCRIPTION
        ];
    }
}
