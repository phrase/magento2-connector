<?php

namespace Memsource\Connector\Model\Account;

use Magento\Framework\Data\Collection;
use Magento\Framework\DataObject;
use Magento\User\Api\Data\UserInterface;
use Magento\User\Model\ResourceModel\User\CollectionFactory;

class AccountRepository
{
    const UNKNOWN_ACCOUNT = 'Unknown account';
    const ATTRIBUTE_IS_ACTIVE = 'is_active';
    const ATTRIBUTE_ID = 'user_id';

    /** @var CollectionFactory */
    protected $collectionFactory;

    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return string
     */
    public function getAccountName()
    {
        $account = $this->getAccount();

        if ($account->isEmpty()) {
            return self::UNKNOWN_ACCOUNT;
        }

        if ($account->getEmail()) {
            return $account->getEmail();
        }

        return $account->getUserName();
    }

    /**
     * @return UserInterface|DataObject
     */
    public function getAccount()
    {
        $collection = $this->collectionFactory->create();
        $collection->addFilter(self::ATTRIBUTE_IS_ACTIVE, true);
        $collection->setOrder(self::ATTRIBUTE_ID, Collection::SORT_ORDER_ASC);
        $collection->getSelect()->limit(1, 0);

        return $collection->getFirstItem();
    }
}
