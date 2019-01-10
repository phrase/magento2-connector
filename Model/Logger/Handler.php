<?php

namespace Memsource\Connector\Model\Logger;

use \Magento\Framework\Logger\Handler\Base;
use Magento\Framework\Logger\Monolog;
use Memsource\Connector\Model\Logger\File\File;

class Handler extends Base
{
    /**
     * Logging level
     * @var int
     */
    protected $loggerType = Monolog::INFO;

    /**
     * File name
     * @var string
     */
    protected $fileName = File::FILE_PATH;
}
