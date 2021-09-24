<?php
/**
 * Copyright (c) Zengliwei. All rights reserved.
 * Each source file in this distribution is licensed under OSL 3.0, see LICENSE for details.
 */

namespace CrazyCat\Email\Model;

use Magento\Framework\Mail\EmailMessageInterfaceFactory;
use Magento\Framework\Mail\MessageInterfaceFactory;
use Magento\Framework\Mail\MimeMessageInterfaceFactory;
use Magento\Framework\Mail\MimePartInterfaceFactory;

/**
 * @author  Zengliwei <zengliwei@163.com>
 * @url https://github.com/zengliwei/magento2_email
 */
class TransportBuilder extends \Magento\Framework\Mail\Template\TransportBuilder
{
    /**
     * @var array
     */
    protected $attachments = [];

    /**
     * @var string
     */
    protected $store;

    /**
     * @inheritDoc
     */
    protected function reset()
    {
        $this->attachments = [];
        $this->store = null;
        return parent::reset();
    }

    /**
     * Add an attachment
     *
     * @param string $filename
     */
    public function addAttachment($filename)
    {
        $this->attachments[] = $filename;
        return $this;
    }

    /**
     * Add attachments
     *
     * @param array $attachments
     */
    public function addAttachments(array $attachments)
    {
        $this->attachments = array_unique(array_merge($this->attachments, $attachments));
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setTemplateOptions($templateOptions)
    {
        if (isset($templateOptions['store'])) {
            $this->store = $templateOptions['store'];
        }
        return parent::setTemplateOptions($templateOptions);
    }

    /**
     * @inheritDoc
     */
    public function getTransport()
    {
        /** @var Transport $transport */
        $attachments = $this->attachments;
        $store = $this->store;
        $transport = parent::getTransport();
        $transport->addAttachments($attachments);
        $transport->setStore($store);
        return $transport;
    }
}
