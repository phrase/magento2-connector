<?php

namespace Memsource\Connector\Model\Account;

use Memsource\Connector\Api\v1\Account\Response\AccountDetailInterface;

class AccountDetail implements AccountDetailInterface
{
    /**
     * @api
     * @var string
     */
    public $account;

    /**
     * @param string $account
     */
    public function __construct($account)
    {
        $this->account = $account;
    }

    /**
     * @return string
     */
    public function getAccount()
    {
        return $this->account;
    }
}
