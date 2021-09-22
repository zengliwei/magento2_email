<?php
/**
 * Copyright (c) Zengliwei. All rights reserved.
 * Each source file in this distribution is licensed under OSL 3.0, see LICENSE for details.
 */

namespace CrazyCat\Email\Model\Transport\Method;

use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\MessageInterface;

/**
 * @author  Zengliwei <zengliwei@163.com>
 * @url https://github.com/zengliwei/magento2_email
 */
interface MethodInterface
{
    /**
     * Send message
     *
     * @param MessageInterface $message
     * @param int|string       $store
     * @return void
     * @throws MailException
     */
    public function sendMessage($message, $store);
}
