<?php
/**
 * Copyright (c) Zengliwei. All rights reserved.
 * Each source file in this distribution is licensed under OSL 3.0, see LICENSE for details.
 */

namespace CrazyCat\Email\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * @author  Zengliwei <zengliwei@163.com>
 * @url https://github.com/zengliwei/magento2_email
 */
class AuthProtocols implements OptionSourceInterface
{
    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        return [
            ['label' => __('SSL'), 'value' => 'ssl'],
            ['label' => __('TLS'), 'value' => 'tls']
        ];
    }
}
