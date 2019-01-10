<?php

namespace Memsource\Connector\Model\Logger;

use Memsource\Connector\Helper\Configuration;

class LoggerFacade
{
    /** @var Configuration */
    private $configuration;

    /** @var Logger */
    private $logger;

    public function __construct(
        Configuration $configuration,
        Logger $logger
    ) {
        $this->configuration = $configuration;
        $this->logger = $logger;
    }

    /**
     * @param $message string
     * @param $level int
     * @param $params array
     * @return bool
     */
    public function log($message, $level = \Monolog\Logger::INFO, $params = [])
    {
        if ($this->configuration->isEnabledDebugMode()) {
            $this->logger->log($level, $message, $params);

            return false;
        }

        return true;
    }
}
