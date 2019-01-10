<?php

namespace Memsource\Connector\Controller\Adminhtml\Log;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Memsource\Connector\Model\Logger\File\FileFacade;

class EmptyFile extends Action
{
    /** @var FileFacade */
    private $fileFacade;

    public function __construct(
        Context $context,
        FileFacade $fileFacade
    ) {
        parent::__construct($context);
        $this->fileFacade = $fileFacade;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $this->fileFacade->emptyFile();
        $this->messageManager->addSuccessMessage('Log file has been emptied.');
        $this->_redirect('adminhtml/system_config/edit/section/memsourceconnector');
    }
}
