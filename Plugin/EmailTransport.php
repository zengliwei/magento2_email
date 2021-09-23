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
use Exception;
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
     * Get config
     *
     * @param string $path
     */
    private function getConfig($path)
    {
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $this->registry->registry('template_option_store')
        );
    }

    /**
     * Around sendMessage
     *
     * @param TransportInterface $subject
     * @param Closure            $proceed
     * @return void
     */
    public function aroundSendMessage(TransportInterface $subject, Closure $proceed)
    {
        switch ($this->getConfig('system/smtp/transport_method')) {
            case Method\Smtp::METHOD:
                $host = $this->getConfig('system/smtp/host');
                $port = $this->getConfig('system/smtp/port');
                $user = $this->getConfig('system/smtp/smtp_user');
                $password = $this->getConfig('system/smtp/smtp_password');
                $authMethod = $this->getConfig('system/smtp/auth_method');
                $authProtocol = $this->getConfig('system/smtp/auth_protocol');

                $message = $subject->getMessage();
                if ($this->getConfig('system/smtp/from_smtp_user')) {
                    $message->setFrom($user);
                }

                try {
                    $this->smtp->sendMessage($message, $host, $port, $user, $password, $authMethod, $authProtocol);
                    $error = null;
                } catch (Exception $e) {
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
                return;

            default:
                $proceed();
        }
    }
}
