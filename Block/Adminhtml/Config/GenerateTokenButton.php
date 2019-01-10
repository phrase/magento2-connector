<?php

namespace Memsource\Connector\Block\Adminhtml\Config;

use Magento\Config\Block\System\Config\Form\Field;

class GenerateTokenButton extends Field
{
    /** @var int */
    const TOKEN_LENGTH = 32;

    /**
     * @var string
     */
    protected $_template = 'Memsource_Connector::config/generate_token_button.phtml';

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
     * @return string
     */
    public static function generateToken()
    {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
        for ($i = 0; $i < self::TOKEN_LENGTH; $i++) {
            $key .= $keys[array_rand($keys)];
        }
        return $key;
    }
}
