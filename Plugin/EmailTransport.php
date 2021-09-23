<?php
/**
 * Copyright (c) Zengliwei. All rights reserved.
 * Each source file in this distribution is licensed under OSL 3.0, see LICENSE for details.
 */

namespace CrazyCat\Email\Plugin;

use Closure;
use CrazyCat\Base\Helper\Logger;
use CrazyCat\Email\Helper\Registry;
use CrazyCat\Email\Model\Transport\Method;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Mail\TransportInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * @author  Zengliwei <zengliwei@163.com>
 * @url https://github.com/zengliwei/magento2_email
 */
class EmailTransport
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Method\Smtp
     */
    private $smtp;

    /**
     * @param Logger               $logger
     * @param Registry             $registry
     * @param Method\Smtp          $smtpMethod
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Logger $logger,
        Registry $registry,
        Method\Smtp $smtpMethod,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->logger = $logger;
        $this->registry = $registry;
        $this->scopeConfig = $scopeConfig;
        $this->smtp = $smtpMethod;
    }

    /**
     * Around sendMessage
     *
     * @param TransportInterface $subject
     * @param Closure            $proceed
     * @return mixed
     */
    public function aroundSendMessage(TransportInterface $subject, Closure $proceed)
    {
        switch ($this->scopeConfig->getValue('system/smtp/transport_method')) {
            case Method\Smtp::METHOD:
                $store = $this->registry->registry('template_option_store');
                $message = $subject->getMessage();

                $host = $this->scopeConfig->getValue('system/smtp/host', ScopeInterface::SCOPE_STORE, $store);
                $port = $this->scopeConfig->getValue('system/smtp/port', ScopeInterface::SCOPE_STORE, $store);
                $user = $this->scopeConfig->getValue('system/smtp/smtp_user', ScopeInterface::SCOPE_STORE, $store);
                $password = $this->scopeConfig->getValue(
                    'system/smtp/smtp_password',
                    ScopeInterface::SCOPE_STORE,
                    $store
                );
                $authMethod = $this->scopeConfig->getValue(
                    'system/smtp/auth_method',
                    ScopeInterface::SCOPE_STORE,
                    $store
                );
                $authProtocol = $this->scopeConfig->getValue(
                    'system/smtp/auth_protocol',
                    ScopeInterface::SCOPE_STORE,
                    $store
                );

                if ($this->scopeConfig->isSetFlag('system/smtp/from_smtp_user', ScopeInterface::SCOPE_STORE, $store)) {
                    $message->setFrom($user);
                }
                if ($this->scopeConfig->isSetFlag('system/smtp/debug', ScopeInterface::SCOPE_STORE, $store)) {
                    $this->logger->log(
                        sprintf(
                            "SMTP mail sent\n" .
                            "host: %s\nport: %s\nauth method: %s\nauth protocol: %s\nuser: %s\npassword: %s",
                            $host,
                            $port,
                            $authMethod,
                            $authProtocol,
                            $user,
                            $password
                        ),
                        'email/' . date('Y') . '/' . date('m') . '/' . date('Y-m-d') . '.log'
                    );
                }

                return $this->smtp->sendMessage(
                    $message,
                    $host,
                    $port,
                    $user,
                    $password,
                    $authMethod,
                    $authProtocol
                );

            default:
                return $proceed();
        }
    }
}
