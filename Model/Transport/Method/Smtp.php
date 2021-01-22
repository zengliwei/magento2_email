<?php
/*
 * Copyright (c) 2020 Zengliwei
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the
 * Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFINGEMENT. IN NO EVENT SHALL THE AUTHORS
 * OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Common\Email\Model\Transport\Method;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Zend\Mail\Message;
use Zend\Mail\Transport\SmtpOptions;

/**
 * @package Common\Email
 * @author  Zengliwei <zengliwei@163.com>
 * @url https://github.com/zengliwei/magento2_email
 */
class Smtp implements MethodInterface
{
    public const METHOD = 'smtp';
    public const METHOD_NAME = 'SMTP';

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @inheritDoc
     */
    public function sendMessage($message, $store)
    {
        $host = $this->scopeConfig->getValue('system/smtp/host', ScopeInterface::SCOPE_STORE, $store);
        $port = $this->scopeConfig->getValue('system/smtp/port', ScopeInterface::SCOPE_STORE, $store);
        $username = $this->scopeConfig->getValue('system/smtp/smtp_user', ScopeInterface::SCOPE_STORE, $store);
        $password = $this->scopeConfig->getValue('system/smtp/smtp_password', ScopeInterface::SCOPE_STORE, $store);

        (new \Zend\Mail\Transport\Smtp(
            new SmtpOptions(
                [
                    'host'              => $host,
                    'port'              => $port,
                    'connection_class'  => 'login',
                    'connection_config' => [
                        'username' => $username,
                        'password' => $password,
                        'ssl'      => 'tls'
                    ]
                ]
            )
        ))->send(Message::fromString($message->getRawMessage())->setEncoding('utf-8'));
    }
}
