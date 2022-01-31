<?php

namespace Memsource\Connector\Model\Content\TranslatableContent;

use Magento\Catalog\Model\CategoryRepository as MagentoCategoryRepository;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\Filter\FilterManager;
use Magento\Store\Model\StoreManagerInterface;

class CategoryRepository extends AbstractContentRepository
{
    const CONTENT_TYPE = 'categories';

    const ATTRIBUTE_ID = 'id';
    const ATTRIBUTE_NAME = 'name';
    const ATTRIBUTE_DESCRIPTION = 'description';
    const ATTRIBUTE_META_TITLE = 'meta_title';
    const ATTRIBUTE_META_KEYWORD = 'meta_keywords';
    const ATTRIBUTE_META_DESCRIPTION = 'meta_description';

    /** @var CollectionFactory */
    protected $categoryCollectionFactory;

    /** @var FilterManager */
    protected $filterManager;

    /** @var MagentoCategoryRepository */
    protected $categoryRepository;

    /** @var StoreManagerInterface */
    protected $storeManager;

    public function __construct(
        CollectionFactory $categoryCollectionFactory,
        FilterManager $filterManager,
        MagentoCategoryRepository $categoryRepository,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->filterManager = $filterManager;
        $this->categoryRepository = $categoryRepository;
        $this->storeManager = $storeManager;
        parent::__construct($scopeConfig);
    }

    /**
     * @inheritdoc
     */
    public function update($storeId, $contentId, $serializedBody)
    {
        $sourceCategory = $this->findItemById($contentId, $storeId);
        $translatedCategory = $this->deserializeItem($serializedBody, $sourceCategory);
        $this->storeManager->setCurrentStore($storeId);
        $this->categoryRepository->save($translatedCategory);

        return $this->getOne($contentId, $storeId);
    }

    /**
     * @inheritdoc
     */
    protected function findTotalItemsCount($storeId)
    {
        $collection = $this->categoryCollectionFactory->create();
        $collection->setStoreId($storeId);

        return $collection->getSize();
    }

    /**
     * @param int $storeId
     * @param int $limit
     * @param int $offset
     * @return CategoryInterface[]
     */
    protected function findItems($storeId, $limit, $offset)
    {
        $collection = $this->categoryCollectionFactory->create();
        $collection->setStoreId($storeId);
        $collection->addAttributeToSelect($this->getTranslatableAttributes());
        $collection->setOrder(self::ATTRIBUTE_ID, Select::SQL_ASC);
        $collection->getSelect()->limit($limit, $offset);

        return $collection->getItems() ?: [];
    }

    /**
     * @param int $itemId
     * @param int $storeId
     * @return CategoryInterface
     */
    protected function findItemById($itemId, $storeId)
    {
        return $this->categoryRepository->get($itemId, $storeId);
    }

    /**
     * @param CategoryInterface $item
     * @param int $storeId
     * @param string|null $serializedBody
     * @return Content
     */
    protected function createContent($item, $storeId, $serializedBody = null)
    {
        return new Content($item->getId(), $item->getName(), $item->getUpdatedAt(), $serializedBody);
    }

    /**
     * @param CategoryInterface $item
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
     * @param CategoryInterface $item
     * @return CategoryInterface
     */
    protected function createItemFromArray($deserialized, $item)
    {
        $item->setName($deserialized[$this->getMainTranslatableAttribute()]);
        $item->setIsActive(true);
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
            self::ATTRIBUTE_META_DESCRIPTION,
            self::ATTRIBUTE_META_KEYWORD,
            self::ATTRIBUTE_META_TITLE,
        ];
    }
}
