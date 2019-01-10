<?php

namespace Memsource\Connector\Block\Adminhtml\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Memsource\Connector\Model\Logger\File\File;
use Memsource\Connector\Model\Logger\File\FileFacade;

class LogFileDownload extends Field
{
    /** @var string */
    protected $_template = 'Memsource_Connector::config/log_file_download.phtml';

    /** @var FileFacade */
    private $fileFacade;

    public function __construct(
        Context $context,
        FileFacade $fileFacade
    ) {
    
        parent::__construct($context, []);
        $this->fileFacade = $fileFacade;
    }

    /**
     * Remove scope label
     *
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    /**
     * Return element html
     *
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->_toHtml();
    }

    /**
     * Get file.
     * @return File
     */
    public function getFile()
    {
        return $this->fileFacade->get();
    }

    /**
     * Get link to controller for download log file.
     * @return string
     */
    public function getFileDownloadLink()
    {
        return $this->getUrl('memsourceconnector/Log/Download');
    }

    /**
     * Get link to controller for empty log file.
     * @return string
     */
    public function getFileEmptyLink()
    {
        return $this->getUrl('memsourceconnector/Log/EmptyFile');
    }
}
