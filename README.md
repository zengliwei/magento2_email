# SMTP module for Magento 2

Use SMTP to send emails.

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

1. Go to `STORES / Settings / Configuration > ADVANCED / System > Mail Sending Settings` of admin panel.
2. Switch the `Email Transport Method` to `SMTP`.
3. Fill in the `Host`, `Port`, `SMTP User`, `SMTP Password` and click the Save Config button.
4. Fill in an email address into the input box above the Test Email button, clich the button to check whether it works
