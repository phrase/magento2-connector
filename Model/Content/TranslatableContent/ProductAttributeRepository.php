<?php

namespace Memsource\Connector\Model\Content\TranslatableContent;

use Magento\Eav\Api\Data\AttributeInterface;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Eav\Model\Entity\Attribute\FrontendLabel;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use Memsource\Connector\Api\v1\Content\ContentApi;
use Magento\Framework\App\ResourceConnection;

class ProductAttributeRepository extends AbstractContentRepository
{
    const CONTENT_TYPE = 'product_attributes';

    const ENTITY_TYPE = 'catalog_product';

    /** @var StoreManagerInterface */
    protected $storeManager;

    /** @var SearchCriteriaBuilder */
    protected $searchCriteriaBuilder;

    /** @var AttributeRepositoryInterface */
    protected $attributeRepository;

    /** @var ResourceConnection */
    protected $resourceConnection;

    /** @var Context */
    protected $context;

    /** @var Registry */
    protected $registry;

    /** @var AbstractResource|null */
    protected $resource;

    /** @var AbstractDb|null */
    protected $resourceCollection;

    public function __construct(
        StoreManagerInterface $storeManager,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        AttributeRepositoryInterface $attributeRepository,
        ResourceConnection $resourceConnection,
        ScopeConfigInterface $scopeConfig,
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null
    ) {
        $this->storeManager = $storeManager;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->attributeRepository = $attributeRepository;
        $this->resourceConnection = $resourceConnection;
        $this->context = $context;
        $this->registry = $registry;
        $this->resource = $resource;
        $this->resourceCollection = $resourceCollection;
        parent::__construct($scopeConfig);
    }

    /**
     * @inheritdoc
     */
    protected function findTotalItemsCount($storeId)
    {
        $items = $this->findItems($storeId, ContentApi::DEFAULT_LIMIT, ContentApi::DEFAULT_OFFSET);

        return count($items);
    }

    /**
     * @param int $storeId
     * @param int $limit
     * @param int $offset
     * @return AttributeInterface[]
     */
    protected function findItems($storeId, $limit, $offset)
    {
        $attributeRepository = $this->attributeRepository->getList(
            self::ENTITY_TYPE,
            $this->searchCriteriaBuilder->create()
        );

        $result = [];

        foreach ($attributeRepository->getItems() as $attribute) {
            if (!empty(trim($attribute->getDefaultFrontendLabel()))) {
                $result[] = $attribute;
            }
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function update($storeId, $contentId, $serializedBody)
    {
        $item = $this->findItemById($contentId, $storeId);
        $frontendLabels = $item->getFrontendLabels();
        $found = false;

        preg_match('/<title>(.*?)<\/title>/s', $serializedBody, $titleMatch);

        foreach ($frontendLabels as $frontendLabel) {
            if ($frontendLabel->getStoreId() == $storeId) {
                $frontendLabel->setLabel($titleMatch[1]);
                $found = true;
            }
        }

        if (!$found) {
            if (isset($frontendLabels[0])) {
                $newFrontendLabel = clone $frontendLabels[0];
            } else {
                $newFrontendLabel = new FrontendLabel(
                    $this->context,
                    $this->registry,
                    $this->resource,
                    $this->resourceCollection
                );
            }

            $newFrontendLabel->setStoreId($storeId);
            $newFrontendLabel->setLabel($titleMatch[1]);
            $frontendLabels[] = $newFrontendLabel;
            $item->setFrontendLabels($frontendLabels);
        }

        $this->attributeRepository->save($item);

        $pattern = '/(<div id="(\d+)">(.*?)<!-- end:\d+ --><\/div>)/s';
        preg_match_all($pattern, $serializedBody, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            if (empty(trim($match[2]))) {
                continue;
            }

            $this->storeAttributeOptionValue((int) $match[2], $storeId, $match[3]);
        }

        return $this->getOne($contentId, $storeId);
    }

    /**
     * @param int $optionId
     * @param int $storeId
     * @param string $value
     */
    private function storeAttributeOptionValue($optionId, $storeId, $value)
    {
        $connection = $this->resourceConnection->getConnection();
        $table = $connection->getTableName('eav_attribute_option_value');

        $query = "SELECT `value_id` FROM `" . $table . "` WHERE `option_id` = '$optionId' AND `store_id` = '$storeId'";
        $optionValueId = $connection->fetchOne($query);

        if ($optionValueId === false) {
            $query = "INSERT INTO `" . $table . "` (`option_id`, `store_id`, `value`) VALUES ('$optionId', '$storeId', '$value')";
        } else {
            $query = "UPDATE `" . $table . "` SET `value` = '$value' WHERE `value_id` = '$optionValueId'";
        }

        $connection->query($query);
    }

    /**
     * @param int $itemId
     * @param int $storeId
     * @return AttributeInterface
     */
    protected function findItemById($itemId, $storeId)
    {
        return $this->attributeRepository->get(self::ENTITY_TYPE, $itemId);
    }

    /**
     * @param AttributeInterface $item
     * @param int $storeId
     * @return string
     */
    protected function serializeItem($item, $storeId)
    {
        $serialized = '';
        $this->storeManager->setCurrentStore($storeId);

        foreach ($item->getOptions() as $option) {
            if (!empty(trim($option->getLabel()))) {
                $serialized .= $this->serializeAttribute($option->getValue(), $option->getLabel());
            }
        }

        return $serialized;
    }

    /**
     * @param AttributeInterface $item
     * @param int $storeId
     * @param string|null $serializedBody
     * @return Content
     */
    protected function createContent($item, $storeId, $serializedBody = null)
    {
        foreach ($item->getFrontendLabels() as $label) {
            if ($label->getStoreId() == $storeId) {
                $name = $label->getLabel();
            }
        }

        if (empty($name)) {
            $name = $item->getDefaultFrontendLabel();
        }

        return new Content($item->getAttributeId(), $name, null, $serializedBody);
    }

    /**
     * @inheridoc
     */
    protected function deserializeItem($serialized, $item)
    {
    }

    /**
     * @inheridoc
     */
    protected function createItemFromArray($deserialized, $item)
    {
    }

    /**
     * @inheridoc
     */
    protected function getAttributeValue($item, $attributeName)
    {
    }

    /**
     * @inheritdoc
     */
    protected function getMainTranslatableAttribute()
    {
    }

    /**
     * @inheritdoc
     */
    protected function getTranslatableAttributes()
    {
        return [];
    }
}
