<?php

namespace Memsource\Connector\Model\Validator;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Webapi\Exception;
use Magento\Store\Model\StoreManagerInterface;
use Memsource\Connector\Helper\Configuration;

class RequestValidator
{
    /* Error codes. */
    const ERR_MISSING_CONFIG = 400;
    const ERR_INVALID_TOKEN = 401;
    const ERR_INVALID_CONTENT_TYPE = 402;
    const ERR_UNKNOWN_STORE_ID = 403;

    /** @var Configuration */
    private $configuration;

    /** @var StoreManagerInterface */
    private $storeManager;

    public function __construct(
        Configuration $configuration,
        StoreManagerInterface $storeManager
    ) {
        $this->configuration = $configuration;
        $this->storeManager = $storeManager;
    }

    /**
     * Token validation used in every API request.
     * @param $token string
     * @throws Exception
     */
    public function validateToken($token)
    {
        $storedConfigurationToken = $this->configuration->getToken();

        if (!$storedConfigurationToken) {
            throw new Exception(
                __('Missing token in configuration.'),
                self::ERR_MISSING_CONFIG,
                Exception::HTTP_BAD_REQUEST
            );
        }

        if ($token !== $storedConfigurationToken) {
            throw new Exception(
                __('Invalid token.'),
                self::ERR_INVALID_TOKEN,
                Exception::HTTP_UNAUTHORIZED
            );
        }
    }

    /**
     * @param string $contentType
     * @param string[] $supportedContentTypes
     * @throws Exception
     */
    public function validateContentType($contentType, $supportedContentTypes)
    {
        if (!in_array($contentType, $supportedContentTypes, true)) {
            throw new Exception(
                __("Unsupported content type '$contentType'"),
                self::ERR_INVALID_CONTENT_TYPE,
                Exception::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @param string|int $storeId
     * @throws Exception
     */
    public function validateStoreId($storeId)
    {
        try {
            $this->storeManager->getStore($storeId);
        } catch (NoSuchEntityException $ex) {
            throw new Exception(
                __("No store found for storeId '$storeId': " . $ex->getMessage()),
                self::ERR_UNKNOWN_STORE_ID,
                Exception::HTTP_BAD_REQUEST
            );
        }
    }
}
