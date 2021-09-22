<?php
/**
 * Copyright (c) Zengliwei. All rights reserved.
 * Each source file in this distribution is licensed under OSL 3.0, see LICENSE for details.
 */

namespace CrazyCat\Email\Model\Transport\Method;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Zend\Mail\Message;
use Zend\Mail\Transport\SmtpOptions;

/**
 * @author  Zengliwei <zengliwei@163.com>
 * @url https://github.com/zengliwei/magento2_email
 */
class Smtp implements MethodInterface
{
    public const METHOD = 'smtp';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

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
