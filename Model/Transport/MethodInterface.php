<?php
/**
 * Copyright (c) Zengliwei. All rights reserved.
 * Each source file in this distribution is licensed under OSL 3.0, see LICENSE for details.
 */

namespace CrazyCat\Email\Model\Transport;

use Laminas\Mail\Message;

/**
 * @author  Zengliwei <zengliwei@163.com>
 * @url https://github.com/zengliwei/magento2_email
 */
interface MethodInterface
{
    /**
     * Send email with given message
     *
     * @param Message $message
     */
    public function send($message);
}
