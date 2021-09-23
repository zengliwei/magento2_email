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
class AuthMethods implements OptionSourceInterface
{
    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        return [
            ['label' => __('CRAM-md5'), 'value' => 'crammd5'],
            ['label' => __('Login'), 'value' => 'login'],
            ['label' => __('Plain'), 'value' => 'plain']
        ];
    }
}
