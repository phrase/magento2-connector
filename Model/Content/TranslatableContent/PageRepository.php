<?php

namespace Memsource\Connector\Model\Content\TranslatableContent;

use Magento\Cms\Model\Page;
use Magento\Cms\Model\PageFactory;
use Magento\Cms\Model\PageRepository as MagentoPageRepository;
use Magento\Cms\Model\ResourceModel\Page\CollectionFactory;
use Magento\Cms\Api\Data\PageInterface;
use Magento\CmsUrlRewrite\Model\CmsPageUrlPathGenerator;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DB\Select;
use Memsource\Connector\Model\Mapping\Mapping;
use Memsource\Connector\Model\Mapping\MappingFacade;
use Memsource\Connector\Model\ResourceModel\Mapping\Collection as MappingCollection;

class PageRepository extends AbstractContentRepository
{
    const CONTENT_TYPE = 'pages';

    /** @var CollectionFactory */
    protected $pageCollectionFactory;

    /** @var MagentoPageRepository */
    protected $pageRepository;

    /** @var MappingCollection */
    protected $mappingCollection;

    /** @var MappingFacade */
    protected $mappingFacade;

    /** @var PageFactory */
    protected $pageFactory;

    /** @var CmsPageUrlPathGenerator */
    protected $pageUrlGenerator;

    public function __construct(
        CmsPageUrlPathGenerator $pageUrlGenerator,
        CollectionFactory $productCollectionFactory,
        MagentoPageRepository $pageRepository,
        MappingCollection $mappingCollection,
        MappingFacade $mappingFacade,
        PageFactory $pageFactory,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->pageUrlGenerator = $pageUrlGenerator;
        $this->pageCollectionFactory = $productCollectionFactory;
        $this->pageRepository = $pageRepository;
        $this->mappingCollection = $mappingCollection;
        $this->mappingFacade = $mappingFacade;
        $this->pageFactory = $pageFactory;
        parent::__construct($scopeConfig);
    }

    /**
     * @inheritdoc
     */
    public function update($storeId, $contentId, $serializedBody)
    {
        $sourcePage = $this->pageRepository->getById($contentId);
        $lang = $this->getLangCodeByStoreId($storeId);
        $mapping = $this->mappingCollection->getOneBySourceIdAndTypeAndLang($contentId, Mapping::PAGE_TYPE, $lang);

        /** @var Page $translatedPage */
        if ($mapping === false) {
            $translatedPage = $this->pageFactory->create();
            $translatedPage = $this->deserializeItem($serializedBody, $translatedPage);
            $translatedPage->setPageLayout($sourcePage->getPageLayout());
            $translatedPage->setStoreId([$storeId]);
            $translatedPage->setIdentifier($sourcePage->getIdentifier());
            $this->pageRepository->save($translatedPage);
            $this->mappingFacade->add($contentId, $translatedPage->getId(), Mapping::PAGE_TYPE, $lang);
        } else {
            $translatedPage = $this->pageRepository->getById($mapping->getTargetId());
            $translatedPage = $this->deserializeItem($serializedBody, $translatedPage);
            $translatedPage->setData('stores', array_merge($translatedPage->getStoreId(), [$storeId]));
            $this->pageRepository->save($translatedPage);
        }

        return $this->getOne($translatedPage->getId(), $storeId);
    }

    /**
     * @inheritdoc
     */
    protected function findTotalItemsCount($storeId)
    {
        $collection = $this->pageCollectionFactory->create();
        $collection->addStoreFilter($storeId);

        return $collection->getSize();
    }

    /**
     * @param int $storeId
     * @param int $limit
     * @param int $offset
     * @return PageInterface[]
     */
    protected function findItems($storeId, $limit, $offset)
    {
        $collection = $this->pageCollectionFactory->create();
        $collection->addStoreFilter($storeId);
        $collection->setOrder(PageInterface::PAGE_ID, Select::SQL_ASC);
        $collection->getSelect()->limit($limit, $offset);

        return $collection->getItems() ?: [];
    }

    /**
     * @param int $itemId
     * @return PageInterface
     */
    protected function findItemById($itemId, $storeId)
    {
        return $this->pageRepository->getById($itemId);
    }

    /**
     * @param PageInterface $page
     * @param int $storeId
     * @param string|null $serializedBody
     * @return Content
     */
    protected function createContent($page, $storeId, $serializedBody = null)
    {
        return new Content($page->getId(), $page->getTitle(), $page->getUpdateTime(), $serializedBody);
    }

    /**
     * @param PageInterface $item
     * @param string $attributeName
     * @return string
     */
    protected function getAttributeValue($item, $attributeName)
    {
        $methodName = "get$attributeName";
        $attributeValue = $item->$methodName();

        if ($attributeValue) {
            return $attributeValue;
        }

        return '';
    }

    /**
     * @param array $deserialized
     * @param PageInterface $item
     * @return PageInterface
     */
    protected function createItemFromArray($deserialized, $item)
    {
        foreach ($deserialized as $attributeName => $attributeValue) {
            $methodName = 'set' . $this->translateAttributeToMethod($attributeName);

            if (method_exists($item, $methodName)) {
                $attributeValue = $this->decodeWidgets($attributeValue);
                $item->$methodName($attributeValue);
            }
        }

        return $item;
    }

    /**
     * Convert attribute name in 'snake_case' to method name in 'CamelCase'.
     * @param string $attribute
     * @return string
     */
    private function translateAttributeToMethod($attribute)
    {
        return str_replace('_', '', ucwords($attribute, '_'));
    }

    /**
     * @inheritdoc
     */
    protected function getMainTranslatableAttribute()
    {
        return PageInterface::TITLE;
    }

    /**
     * @inheritdoc
     */
    protected function getTranslatableAttributes()
    {
        return [
            PageInterface::TITLE,
            PageInterface::CONTENT_HEADING,
            PageInterface::CONTENT,
            PageInterface::META_TITLE,
            PageInterface::META_KEYWORDS,
            PageInterface::META_DESCRIPTION,
        ];
    }
}
