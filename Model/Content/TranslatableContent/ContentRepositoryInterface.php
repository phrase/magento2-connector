<?php

namespace Memsource\Connector\Model\Content\TranslatableContent;

interface ContentRepositoryInterface
{
    /**
     * @param int $storeId
     * @param int $limit
     * @param int $offset
     * @return ContentList
     */
    public function getList($storeId, $limit, $offset);

    /**
     * @param int $contentId
     * @param int $storeId
     * @return ContentDetail
     */
    public function getOne($contentId, $storeId);

    /**
     * @param int $storeId
     * @param int $contentId
     * @param string $serializedBody
     * @return ContentDetail
     */
    public function update($storeId, $contentId, $serializedBody);
}
