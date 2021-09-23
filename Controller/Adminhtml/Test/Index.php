<?php
/**
 * Copyright (c) Zengliwei. All rights reserved.
 * Each source file in this distribution is licensed under OSL 3.0, see LICENSE for details.
 */

namespace CrazyCat\Email\Controller\Adminhtml\Test;

use CrazyCat\Email\Model\Transport\Method\Smtp;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\Store;

/**
 * @author  Zengliwei <zengliwei@163.com>
 * @url https://github.com/zengliwei/magento2_email
 */
class Index extends Action implements HttpPostActionInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Smtp
     */
    private $smtp;

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param Smtp                 $smtpMethod
     * @param TransportBuilder     $transportBuilder
     * @param Context              $context
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Smtp $smtpMethod,
        TransportBuilder $transportBuilder,
        Context $context
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->smtp = $smtpMethod;
        $this->transportBuilder = $transportBuilder;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        try {
            $success = true;
            $message = null;

            $mail = $this->getRequest()->getParam('email');
            $host = $this->getRequest()->getParam('host');
            $port = $this->getRequest()->getParam('port');
            $user = $this->getRequest()->getParam('user');
            $password = $this->getRequest()->getParam('password');

            $authMethod = $this->getRequest()->getParam('auth_method')
                ?: $this->scopeConfig->getValue('system/smtp/auth_method');

            $authProtocol = $this->getRequest()->getParam('auth_protocol')
                ?: $this->scopeConfig->getValue('system/smtp/auth_protocol');

            $emailMessage = $this->transportBuilder
                ->setTemplateIdentifier('email_test_template')
                ->setTemplateVars([])
                ->setTemplateOptions(['area' => Area::AREA_ADMINHTML, 'store' => Store::DEFAULT_STORE_ID])
                ->setFromByScope(['email' => $mail, 'name' => 'Email Tester'])
                ->addTo($mail)
                ->getTransport()
                ->getMessage();

            $this->smtp->sendMessage($emailMessage, $host, $port, $user, $password, $authMethod, $authProtocol);
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        return $result->setData(['success' => $success, 'message' => $message]);
    }
}
