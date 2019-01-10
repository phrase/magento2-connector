<?php

namespace Memsource\Connector\Model\Content\TranslatableContent;

use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Cms\Api\Data\PageInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;

abstract class AbstractContentRepository implements ContentRepositoryInterface
{
    /** @var ScopeConfigInterface */
    protected $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @inheritdoc
     */
    public function getList($storeId, $limit, $offset)
    {
        $content = [];
        $totalBlocksCount = $this->findTotalItemsCount($storeId);
        $items = $this->findItems($storeId, $limit, $offset);

        foreach ($items as $item) {
            $content[] = $this->createContent($item);
        }

        return new ContentList($content, $totalBlocksCount, count($content), $limit, $offset);
    }

    /**
     * @inheritdoc
     */
    public function getOne($contentId, $storeId)
    {
        $item = $this->findItemById($contentId, $storeId);
        $serializedBody = $this->serializeItem($item);

        return $this->createContentDetail($item, $serializedBody);
    }

    /**
     * @inheritdoc
     */
    abstract public function update($storeId, $contentId, $serializedBody);

    /**
     * @param int $storeId
     * @return int
     */
    abstract protected function findTotalItemsCount($storeId);

    /**
     * @param int $storeId
     * @param int $limit
     * @param int $offset
     * @return BlockInterface[]|PageInterface[]|CategoryInterface[]|ProductInterface[]|DataObject[]
     */
    abstract protected function findItems($storeId, $limit, $offset);

    /**
     * @param int $itemId
     * @param int $storeId
     * @return BlockInterface|PageInterface|CategoryInterface|ProductInterface|DataObject
     */
    abstract protected function findItemById($itemId, $storeId);

    /**
     * @param int $storeId
     * @return string
     */
    protected function getLangCodeByStoreId($storeId)
    {
        $lang = $this->scopeConfig->getValue('general/locale/code', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);

        return strtolower($lang);
    }

    /**
     * @param BlockInterface|PageInterface|CategoryInterface|ProductInterface $item
     * @param string|null $serializedBody
     * @return Content
     */
    abstract protected function createContent($item, $serializedBody = null);

    /**
     * @param BlockInterface|PageInterface|CategoryInterface|ProductInterface $item
     * @param string|null $serializedBody
     * @return ContentDetail
     */
    protected function createContentDetail($item, $serializedBody)
    {
        return new ContentDetail($this->createContent($item, $serializedBody));
    }

    /**
     * @param BlockInterface|PageInterface|CategoryInterface|ProductInterface $item
     * @return string
     */
    protected function serializeItem($item)
    {
        $serializedBlock = '';

        foreach ($this->getTranslatableAttributes() as $attributeName) {
            if ($attributeName === $this->getMainTranslatableAttribute()) {
                continue;
            }

            $attributeValue = $this->getAttributeValue($item, $attributeName);
            $serializedBlock .= $this->serializeAttribute($attributeName, $attributeValue);
        }

        return $serializedBlock;
    }

    /**
     * @param BlockInterface|PageInterface|CategoryInterface|ProductInterface $item
     * @param string $attributeName
     * @return string
     */
    abstract protected function getAttributeValue($item, $attributeName);

    /**
     * @param string $attributeName
     * @param string $attributeValue
     * @return string
     */
    protected function serializeAttribute($attributeName, $attributeValue)
    {
        $attributeValue = $this->encodeWidgets($attributeValue);

        return sprintf('<div id="%s">%s<!-- end:%s --></div>', $attributeName, $attributeValue, $attributeName);
    }

    /**
     * @param string $serialized
     * @param BlockInterface|PageInterface|CategoryInterface|ProductInterface $item
     * @return BlockInterface|PageInterface|CategoryInterface|ProductInterface
     * @throws \InvalidArgumentException
     */
    protected function deserializeItem($serialized, $item)
    {
        $pattern = $this->getDeserializationPattern();
        preg_match_all($pattern, $serialized, $matches, PREG_SET_ORDER);

        if (!$matches || (count($matches[0]) - 1) !== count($this->getTranslatableAttributes())) {
            throw new \InvalidArgumentException("Invalid format! Raw data: $serialized");
        }

        $deserialized = [];

        foreach ($this->getTranslatableAttributes() as $i => $attribute) {
            $deserialized[$attribute] = $matches[0][$i + 1];
        }

        return $this->createItemFromArray($deserialized, $item);
    }

    /**
     * @return string
     */
    private function getDeserializationPattern()
    {
        $pattern = '';

        foreach ($this->getTranslatableAttributes() as $attribute) {
            if ($attribute === $this->getMainTranslatableAttribute()) {
                $pattern .= '<title>(.*?)<\/title>\s*';
            } else {
                $pattern .= "<div id=\"$attribute\">(.*?)<!-- end:$attribute --><\/div>";
            }
        }

        return "/$pattern/s";
    }

    /**
     * @param array $deserialized
     * @param BlockInterface|PageInterface|CategoryInterface|ProductInterface $item
     * @return BlockInterface|PageInterface|CategoryInterface|ProductInterface
     */
    abstract protected function createItemFromArray($deserialized, $item);

    /**
     * @return string
     */
    abstract protected function getMainTranslatableAttribute();

    /**
     * @return string[]
     */
    abstract protected function getTranslatableAttributes();

    /**
     * @param string $content
     * @return string
     */
    protected function encodeWidgets($content)
    {
        preg_match_all('/{(?:[^{}]++|(?R))*}/', $content, $matches);

        foreach ($matches[0] as $match) {
            $encoded = '<!--widget:' . base64_encode($match) . ':-->';
            $pos = strpos($content, $match);
            $content = substr_replace($content, $encoded, $pos, strlen($match));
        }

        return $content;
    }

    /**
     * @param string $content
     * @return string
     */
    protected function decodeWidgets($content)
    {
        preg_match_all('/<!--widget:([^:]*):-->/m', $content, $widgetMatches);

        foreach ($widgetMatches[0] as $i => $widgetMatch) {
            $decodedWidget = base64_decode($widgetMatches[1][$i]);
            $content = str_replace($widgetMatch, $decodedWidget, $content);
        }

        return $content;
    }
}
