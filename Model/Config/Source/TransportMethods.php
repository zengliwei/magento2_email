<?php
/**
 * Copyright (c) Zengliwei. All rights reserved.
 * Each source file in this distribution is licensed under OSL 3.0, see LICENSE for details.
 */

namespace CrazyCat\Email\Model\Config\Source;

use CrazyCat\Email\Model\Transport;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * @author  Zengliwei <zengliwei@163.com>
 * @url https://github.com/zengliwei/magento2_email
 */
class TransportMethods implements OptionSourceInterface
{
    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        return [
            ['label' => __('Default'), 'value' => Transport\Sendmail::METHOD],
            ['label' => __('SMTP'), 'value' => Transport\Smtp::METHOD]
        ];
    }
}
