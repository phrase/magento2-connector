<?php

namespace Memsource\Connector\Model\Logger\Webapi;

use Memsource\Connector\Model\Logger\LoggerFacade;
use Monolog\Logger;

class ServiceOutputProcessor
{
    /** @var LoggerFacade */
    protected $loggerFacade;

    public function __construct(LoggerFacade $loggerFacade)
    {
        $this->loggerFacade = $loggerFacade;
    }

    /**
     * @param \Magento\Framework\Webapi\ServiceOutputProcessor $subject
     * @param callable $proceed
     * @param mixed $data
     * @param string $serviceClassName
     * @param string $serviceMethodName
     * @return array
     */
    public function aroundProcess(
        \Magento\Framework\Webapi\ServiceOutputProcessor $subject,
        callable $proceed,
        $data,
        $serviceClassName,
        $serviceMethodName
    ) {
    
        $result = $proceed($data, $serviceClassName, $serviceMethodName);
        if (strpos($serviceClassName, 'Memsource\\Connector') !== false) {
            $message = 'Response from: ' . $serviceClassName;
            $this->loggerFacade->log($message, Logger::INFO, $result);
        }
        return $result;
    }
}
