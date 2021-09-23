<?php
/**
 * Copyright (c) Zengliwei. All rights reserved.
 * Each source file in this distribution is licensed under OSL 3.0, see LICENSE for details.
 */

namespace CrazyCat\Email\Plugin;

use CrazyCat\Email\Helper\Registry;
use Magento\Framework\Mail\Template\TransportBuilder;

/**
 * @author  Zengliwei <zengliwei@163.com>
 * @url https://github.com/zengliwei/magento2_email
 */
class EmailTransportBuilder
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param Registry $registry
     */
    public function __construct(
        Registry $registry
    ) {
        $this->registry = $registry;
    }

    /**
     * Before setTemplateOptions
     *
     * @param TransportBuilder $subject
     * @param
     */
    public function beforeSetTemplateOptions(TransportBuilder $subject, $options)
    {
        if (isset($options['store'])) {
            $this->registry->register('template_option_store', $options['store']);
        }
    }
}
