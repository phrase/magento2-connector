<?php

namespace Memsource\Connector\Model\Content\TranslatableContent;

use Magento\Cms\Api\Data\BlockInterface;
use Magento\Cms\Model\Block;
use Magento\Cms\Model\BlockFactory;
use Magento\Cms\Model\BlockRepository as MagentoBlockRepository;
use Magento\Cms\Model\ResourceModel\Block\CollectionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\Filter\FilterManager;
use Memsource\Connector\Model\Mapping\Mapping;
use Memsource\Connector\Model\Mapping\MappingFacade;
use Memsource\Connector\Model\ResourceModel\Mapping\Collection as MappingCollection;

class BlockRepository extends AbstractContentRepository
{
    const CONTENT_TYPE = 'blocks';

    /** @var BlockFactory */
    protected $blockFactory;

    /** @var CollectionFactory */
    protected $blockCollectionFactory;

    /** @var FilterManager */
    protected $filterManager;

    /** @var MagentoBlockRepository */
    protected $blockRepository;

    /** @var MappingCollection */
    protected $mappingCollection;

    /** @var MappingFacade */
    protected $mappingFacade;

    public function __construct(
        BlockFactory $blockFactory,
        CollectionFactory $blockCollectionFactory,
        FilterManager $filterManager,
        MagentoBlockRepository $blockRepository,
        MappingCollection $mappingCollection,
        MappingFacade $mappingFacade,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->blockFactory = $blockFactory;
        $this->blockCollectionFactory = $blockCollectionFactory;
        $this->filterManager = $filterManager;
        $this->blockRepository = $blockRepository;
        $this->mappingCollection = $mappingCollection;
        $this->mappingFacade = $mappingFacade;
        parent::__construct($scopeConfig);
    }

    /**
     * @inheritdoc
     */
    public function update($storeId, $contentId, $serializedBody)
    {
        $lang = $this->getLangCodeByStoreId($storeId);
        $mapping = $this->mappingCollection->getOneBySourceIdAndTypeAndLang($contentId, Mapping::BLOCK_TYPE, $lang);

        /** @var Block $translatedBlock */
        if ($mapping === false) {
            $translatedBlock = $this->blockFactory->create();
            $translatedBlock = $this->deserializeItem($serializedBody, $translatedBlock);
            $translatedBlock->setStoreId([$storeId]);
            $this->blockRepository->save($translatedBlock);
            $this->mappingFacade->add($contentId, $translatedBlock->getId(), Mapping::BLOCK_TYPE, $lang);
        } else {
            $translatedBlock = $this->blockRepository->getById($mapping->getTargetId());
            $translatedBlock = $this->deserializeItem($serializedBody, $translatedBlock);
            $translatedBlock->setData('stores', array_merge($translatedBlock->getStoreId(), [$storeId]));
            $this->blockRepository->save($translatedBlock);
        }

        return $this->getOne($translatedBlock->getId(), $storeId);
    }

    /**
     * @inheritdoc
     */
    protected function findTotalItemsCount($storeId)
    {
        $collection = $this->blockCollectionFactory->create();
        $collection->addStoreFilter($storeId);

        return $collection->getSize();
    }

    /**
     * @param int $storeId
     * @param int $limit
     * @param int $offset
     * @return BlockInterface[]
     */
    protected function findItems($storeId, $limit, $offset)
    {
        $collection = $this->blockCollectionFactory->create();
        $collection->addStoreFilter($storeId);
        $collection->setOrder(BlockInterface::BLOCK_ID, Select::SQL_ASC);
        $collection->getSelect()->limit($limit, $offset);

        return $collection->getItems() ?: [];
    }

    /**
     * @param int $itemId
     * @param int $storeId
     * @return BlockInterface
     */
    protected function findItemById($itemId, $storeId)
    {
        return $this->blockRepository->getById($itemId);
    }

    /**
     * @param BlockInterface $item
     * @param string|null $serializedBody
     * @return Content
     */
    protected function createContent($item, $serializedBody = null)
    {
        return new Content($item->getId(), $item->getTitle(), $item->getUpdateTime(), $serializedBody);
    }

    /**
     * @param BlockInterface $item
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
     * @param BlockInterface $item
     * @return BlockInterface
     */
    protected function createItemFromArray($deserialized, $item)
    {
        foreach ($deserialized as $attributeName => $attributeValue) {
            $methodName = "set$attributeName";

            if (method_exists($item, $methodName)) {
                $attributeValue = $this->decodeWidgets($attributeValue);
                $item->$methodName($attributeValue);
            }
        }

        $item->setIdentifier($this->filterManager->translitUrl($item->getTitle()));

        return $item;
    }

    /**
     * @inheritdoc
     */
    protected function getMainTranslatableAttribute()
    {
        return BlockInterface::TITLE;
    }

    /**
     * @inheritdoc
     */
    protected function getTranslatableAttributes()
    {
        return [
            BlockInterface::TITLE,
            BlockInterface::CONTENT,
        ];
    }
}
