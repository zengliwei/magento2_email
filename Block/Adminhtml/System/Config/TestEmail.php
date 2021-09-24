<?php
/**
 * Copyright (c) Zengliwei. All rights reserved.
 * Each source file in this distribution is licensed under OSL 3.0, see LICENSE for details.
 */

namespace CrazyCat\Email\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * @author  Zengliwei <zengliwei@163.com>
 * @url https://github.com/zengliwei/magento2_email
 */
class TestEmail extends Field
{
    /**
     * @var string
     */
    protected $_template = 'CrazyCat_Email::system/config/test_email.phtml';

    /**
     * @inheritDoc
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $this->addData(
            [
                'html_id' => $element->getHtmlId(),
                'ajax_url' => $this->_urlBuilder->getUrl($element->getData('original_data/button_url')),
                'js_path' => $element->getData('original_data/button_url')
            ]
        );
        return $this->_toHtml();
    }
}
