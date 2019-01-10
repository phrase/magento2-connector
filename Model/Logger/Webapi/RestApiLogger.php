<?php

namespace Memsource\Connector\Model\Logger\Webapi;

use Magento\Framework\App\Request\Http as Request;
use Magento\Webapi\Controller\Rest;
use Memsource\Connector\Model\Logger\LoggerFacade;
use Monolog\Logger;

class RestApiLogger
{
    /** @var LoggerFacade */
    private $loggerFacade;

    public function __construct(LoggerFacade $loggerFacade)
    {
        $this->loggerFacade = $loggerFacade;
    }

    /**
     * @param $subject Rest
     * @param $request Request
     * @return void
     */
    public function beforeDispatch(
        \Magento\Webapi\Controller\Rest $subject,
        Request $request
    ) {
    
        $pathInfo = $request->getPathInfo();
        if (strpos($pathInfo, 'memsource') !== false) {
            $message = sprintf('API request: [%s] %s', $request->getMethod(), $pathInfo);
            $this->loggerFacade->log($message, Logger::INFO, ['params' => $request->getParams()]);
        }
    }
}
