<?php
/**
 * Copyright (c) Zengliwei. All rights reserved.
 * Each source file in this distribution is licensed under OSL 3.0, see LICENSE for details.
 */

namespace CrazyCat\Email\Model\Transport\Method;

use Laminas\Mail\Message;
use Laminas\Mail\Transport\Smtp as Transport;
use Laminas\Mail\Transport\SmtpOptions;
use Magento\Framework\Mail\EmailMessageInterface;

/**
 * @author  Zengliwei <zengliwei@163.com>
 * @url https://github.com/zengliwei/magento2_email
 */
class Smtp
{
    public const METHOD = 'smtp';
    public const METHOD_NAME = 'SMTP';

    /**
     * Send message
     *
     * @param EmailMessageInterface $message
     * @param string                $host
     * @param string                $port
     * @param string                $username
     * @param string                $password
     * @param string                $authMethod
     * @param string                $authProtocol
     */
    public function sendMessage(
        $message,
        $host,
        $port,
        $username,
        $password,
        $authMethod = 'login',
        $authProtocol = 'tls'
    ) {
        return (new Transport(
            new SmtpOptions(
                [
                    'host'              => $host,
                    'port'              => $port,
                    'connection_class'  => $authMethod,
                    'connection_config' => [
                        'username' => $username,
                        'password' => $password,
                        'ssl'      => $authProtocol
                    ]
                ]
            )
        ))->send(Message::fromString($message->getRawMessage())->setEncoding('utf-8'));
    }
}
