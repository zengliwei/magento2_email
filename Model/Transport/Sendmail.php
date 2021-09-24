<?php
/**
 * Copyright (c) Zengliwei. All rights reserved.
 * Each source file in this distribution is licensed under OSL 3.0, see LICENSE for details.
 */

namespace CrazyCat\Email\Model\Transport;

/**
 * @author  Zengliwei <zengliwei@163.com>
 * @url https://github.com/zengliwei/magento2_email
 */
class Sendmail implements MethodInterface
{
    public const METHOD = 'default';

    /**
     * @inheritDoc
     */
    public function send($message)
    {
        (new \Laminas\Mail\Transport\Sendmail())->send($message);
    }
}
