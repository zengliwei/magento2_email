<?php
/**
 * Copyright (c) Zengliwei. All rights reserved.
 * Each source file in this distribution is licensed under OSL 3.0, see LICENSE for details.
 */

namespace CrazyCat\Email\Model;

use Laminas\Mail\Message;
use Laminas\Mime\Mime;
use Laminas\Mime\Part;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\DriverInterface;
use Magento\Framework\Filesystem\Io\File as IoFile;
use Magento\Framework\Mail\EmailMessageInterface;
use Magento\Framework\Mail\TransportInterface;

/**
 * @author  Zengliwei <zengliwei@163.com>
 * @url https://github.com/zengliwei/magento2_email
 */
class Transport implements TransportInterface
{
    /**
     * @var array
     */
    protected $attachments = [];

    /**
     * @var DriverInterface
     */
    protected $filesystemDriver;

    /**
     * @var IoFile
     */
    protected $ioFile;

    /**
     * @var EmailMessageInterface
     */
    protected $message;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var Transport\SendmailFactory
     */
    protected $sendmailFactory;

    /**
     * @var Transport\SmtpFactory
     */
    protected $smtpFactory;

    /**
     * @var string
     */
    protected $store;

    /**
     * @param DriverInterface           $filesystemDriver
     * @param IoFile                    $ioFile
     * @param EmailMessageInterface     $message
     * @param ScopeConfigInterface      $scopeConfig
     * @param Transport\SendmailFactory $sendmailFactory
     * @param Transport\SmtpFactory     $smtpFactory
     */
    public function __construct(
        DriverInterface $filesystemDriver,
        IoFile $ioFile,
        EmailMessageInterface $message,
        ScopeConfigInterface $scopeConfig,
        Transport\SendmailFactory $sendmailFactory,
        Transport\SmtpFactory $smtpFactory
    ) {
        $this->filesystemDriver = $filesystemDriver;
        $this->ioFile = $ioFile;
        $this->message = $message;
        $this->scopeConfig = $scopeConfig;
        $this->sendmailFactory = $sendmailFactory;
        $this->smtpFactory = $smtpFactory;
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
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get store
     *
     * @return string
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     * Set store
     *
     * @param string $store
     */
    public function setStore($store)
    {
        $this->store = $store;
        return $this;
    }

    /**
     * Prepare mail message
     *
     * @return Message
     * @throws FileSystemException
     */
    public function prepareMailMessage()
    {
        $message = Message::fromString($this->message->toString());
        $mimeMessage = $message->getBody();

        $hasAttachment = false;
        foreach ($this->attachments as $attachment) {
            if (!$this->filesystemDriver->isFile($attachment)) {
                continue;
            }
            $hasAttachment = true;
            $pathInfo = $this->ioFile->getPathInfo($attachment);
            $fileInfo = finfo_open(FILEINFO_MIME);
            $mime = finfo_file($fileInfo, $attachment);
            finfo_close($fileInfo);
            $mimePart = new Part($this->filesystemDriver->fileOpen($attachment, 'r'));
            $mimePart->setType($mime)
                ->setFileName($pathInfo['basename'])
                ->setDisposition(Mime::DISPOSITION_ATTACHMENT)
                ->setEncoding(Mime::ENCODING_BASE64);
            $mimeMessage->addPart($attachment);
        }

        if ($hasAttachment) {
            $contentTypeHeader = $message->getHeaders()->get('Content-Type');
            $contentTypeHeader->setType(Mime::MULTIPART_RELATED);
        }

        return $message;
    }

    /**
     * Get transport method
     *
     * @return Transport\MethodInterface
     */
    public function getTransportMethod()
    {
        switch ($this->scopeConfig->getValue('system/smtp/transport_method')) {
            case Transport\Sendmail::METHOD:
                return $this->sendmailFactory->create();

            case Transport\Smtp::METHOD:
                return $this->smtpFactory->create(['store' => $this->store]);
        }
    }

    /**
     * @inheritDoc
     */
    public function sendMessage()
    {
        $this->getTransportMethod()->send($this->prepareMailMessage());
    }
}
