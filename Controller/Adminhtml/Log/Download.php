<?php

namespace Memsource\Connector\Controller\Adminhtml\Log;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Memsource\Connector\Model\Logger\File\File;
use Memsource\Connector\Model\Logger\File\FileFacade;

class Download extends Action
{
    /** @var FileFacade */
    private $fileFacade;

    /** @var FileFactory */
    private $fileFactory;

    public function __construct(
        Action\Context $context,
        FileFacade $fileFacade,
        FileFactory $fileFactory
    ) {

        parent::__construct($context);
        $this->fileFacade = $fileFacade;
        $this->fileFactory = $fileFactory;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $file = $this->fileFacade->get();
        return $this->fileFactory->create(File::FILE_NAME, $file->getContent(), DirectoryList::LOG);
    }
}
