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

namespace Common\Email\Plugin;

use Closure;
use Common\Email\Model\Transport\Method;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\TransportInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * @package Common\Email
 * @author  Zengliwei <zengliwei@163.com>
 * @url https://github.com/zengliwei/magento2_email
 */
class EmailTransport
{
    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @var Method\Smtp
     */
    private Method\Smtp $smtp;

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
     * @param TransportInterface $subject
     * @param Closure            $proceed
     * @return mixed
     * @throws MailException
     */
    public function aroundSendMessage(TransportInterface $subject, Closure $proceed)
    {
        $store = null;
        switch ($this->scopeConfig->getValue('system/smtp/transport_method', ScopeInterface::SCOPE_STORE, $store)) {
            case Method\Smtp::METHOD:
                return $this->smtp->sendMessage($subject->getMessage(), $store);

            default:
                return $proceed();
        }
    }
}
