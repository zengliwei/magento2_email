<?php
/*
 * Copyright (c) Zengliwei. All rights reserved.
 * Each source file in this distribution is licensed under OSL 3.0, see LICENSE for details.
 */

namespace CrazyCat\Email\Model\Transport;

use CrazyCat\Base\Helper\Logger;
use Laminas\Mail\Transport\Exception\RuntimeException;
use Laminas\Mail\Transport\Smtp as Transport;
use Laminas\Mail\Transport\SmtpOptions;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * @author  Zengliwei <zengliwei@163.com>
 * @url https://github.com/zengliwei/magento2_email
 */
class Smtp implements MethodInterface
{
    public const METHOD = 'smtp';

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var string
     */
    protected $store;

    /**
     * @param Logger               $logger
     * @param ScopeConfigInterface $scopeConfig
     * @param null                 $store
     */
    public function __construct(
        Logger $logger,
        ScopeConfigInterface $scopeConfig,
        $store = null
    ) {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->store = $store;
    }

    /**
     * Get config
     *
     * @param string $path
     */
    private function getConfig($path)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE, $this->store);
    }

    /**
     * @inheritDoc
     */
    public function send($message)
    {
        $host = $this->getConfig('system/smtp/host');
        $port = $this->getConfig('system/smtp/port');
        $user = $this->getConfig('system/smtp/smtp_user');
        $password = $this->getConfig('system/smtp/smtp_password');
        $authMethod = $this->getConfig('system/smtp/auth_method');
        $authProtocol = $this->getConfig('system/smtp/auth_protocol');

        if ($this->getConfig('system/smtp/from_smtp_user')) {
            $message->setFrom($user);
        }

        try {
            (new Transport(
                new SmtpOptions(
                    [
                        'host'              => $host,
                        'port'              => $port,
                        'connection_class'  => $authMethod,
                        'connection_config' => [
                            'username' => $user,
                            'password' => $password,
                            'ssl'      => $authProtocol
                        ]
                    ]
                )
            ))->send($message);
            $error = null;
        } catch (RuntimeException $e) {
            $error = $e->getMessage();
        }

        if ($this->getConfig('system/smtp/debug')) {
            $this->logger->log(
                "SMTP mail sent\n" .
                "host: $host\nport: $port\nauth method: $authMethod\nauth protocol: $authProtocol\n" .
                "user: $user\npassword: $password\n" .
                'subject: ' . $message->getSubject() . "\nerror: $error",
                'email/' . date('Y') . '/' . date('m') . '/' . date('Y-m-d') . '.log'
            );
        }
    }
}
