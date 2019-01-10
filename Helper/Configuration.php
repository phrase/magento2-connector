<?php

namespace Memsource\Connector\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use \Magento\Store\Model\Store;

class Configuration extends AbstractHelper
{
    /** @var string */
    const BASE_PATH = 'memsourceconnector';

    /**
     * @return bool
     */
    public function isEnabledDebugMode()
    {
        return $this->getValue($this->getDebugPath()) == '1';
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->getValue($this->getTokenPath());
    }

    /**
     * @return string
     */
    public function getTokenPath()
    {
        return self::BASE_PATH . DIRECTORY_SEPARATOR . 'general' . DIRECTORY_SEPARATOR . 'token';
    }

    /**
     * @return string
     */
    public function getDebugPath()
    {
        return self::BASE_PATH . DIRECTORY_SEPARATOR . 'general' . DIRECTORY_SEPARATOR . 'debug';
    }

    /**
     * @param string $field
     * @return mixed
     */
    private function getValue($field)
    {
        return $this->scopeConfig->getValue($field, ScopeInterface::SCOPE_STORE, Store::DEFAULT_STORE_ID);
    }
}
