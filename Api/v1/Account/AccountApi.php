<?php

namespace Memsource\Connector\Api\v1\Account;

use Memsource\Connector\Model\Account\AccountDetail;
use Memsource\Connector\Model\Account\AccountRepository;
use Memsource\Connector\Model\Validator\RequestValidator;

class AccountApi
{
    /** @var RequestValidator */
    private $requestValidator;

    /** @var AccountRepository */
    private $accountRepository;

    public function __construct(RequestValidator $requestValidator, AccountRepository $accountRepository)
    {
        $this->requestValidator = $requestValidator;
        $this->accountRepository = $accountRepository;
    }

    /**
     * Get detail of admin account.
     * @param string $token
     * @return \Memsource\Connector\Api\v1\Account\Response\AccountDetailInterface
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function getDetail($token)
    {
        $this->requestValidator->validateToken($token);
        $adminAccountName = $this->accountRepository->getAccountName();

        return new AccountDetail($adminAccountName);
    }
}
