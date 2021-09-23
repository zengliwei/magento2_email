<?php
/**
 * Copyright (c) Zengliwei. All rights reserved.
 * Each source file in this distribution is licensed under OSL 3.0, see LICENSE for details.
 */

namespace CrazyCat\Email\Plugin;

use Closure;
use CrazyCat\Email\Model\Transport\Method;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Mail\TransportInterface;

/**
 * @author  Zengliwei <zengliwei@163.com>
 * @url https://github.com/zengliwei/magento2_email
 */
class EmailTransport
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Method\Smtp
     */
    private $smtp;

    /**
     * @param Method\Smtp          $smtpMethod
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Method\Smtp $smtpMethod,
        ScopeConfigInterface $scopeConfig
    ) {
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
                return $this->smtp->sendMessage(
                    $subject->getMessage(),
                    $this->scopeConfig->getValue('system/smtp/host'),
                    $this->scopeConfig->getValue('system/smtp/port'),
                    $this->scopeConfig->getValue('system/smtp/smtp_user'),
                    $this->scopeConfig->getValue('system/smtp/smtp_password'),
                    $this->scopeConfig->getValue('system/smtp/auth_method'),
                    $this->scopeConfig->getValue('system/smtp/auth_protocol')
                );

            default:
                return $proceed();
        }
    }
}
