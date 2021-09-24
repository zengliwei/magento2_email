# SMTP module for Magento 2

- Add SMTP method for sending emails.
- Rebuild transport builder and transport model, make it easier to add attachment

## Installation

1. Execute this command to download the module through composer:<br>
   `composer require CrazyCat_Email`

2. Execute these commands to enable the modules:<br>
   `php bin/magento module:enable CrazyCat_Base`<br>
   `php bin/magento module:enable CrazyCat_Email`

3. Execute these commands to recompile and flush cache:<br>
   `php bin/magento setup:di:compile`<br>
   `php bin/magento cache:flush`

## How to use

### Sending email by SMTP

1. Go to `STORES / Settings / Configuration > ADVANCED / System > Mail Sending Settings` of admin panel.
2. Switch the `Email Transport Method` to `SMTP`.
3. Config in the settings `Host`, `Port`, `SMTP User`, `SMTP Password`, etc.
4. Fill in an email address into the input box above Test Email button, then click the button and check email box
5. If a test mail is received, then everything goes fine. Click the Save Config button to make it work for Magento
   functions

### Sending email with attachment

Use `addAttachment` or `addAttachments` method to add attachment(s) before executing `getTransport`
of `\Magento\Framework\Mail\Template\TransportBuilder`, here is an example:

```php
/** @var \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder */
/** @var string $templateId */
/** @var array $variables */
/** @var string $fromMail */
/** @var string $fromName */
/** @var string $toMail */
/** @var string $filepath  Absolute file path of the attachment */
$transportBuilder
    ->setTemplateIdentifier($templateId)
    ->setTemplateVars($variables)
    ->setTemplateOptions(['area' => Area::AREA_ADMINHTML, 'store' => Store::DEFAULT_STORE_ID])
    ->setFromByScope(['email' => $fromMail, 'name' => $fromName])
    ->addTo($toMail)
    ->addAttachment($filepathA)
    ->addAttachments([$filepathB, $filepathC])
    ->getTransport()
    ->sendMessage();
```
