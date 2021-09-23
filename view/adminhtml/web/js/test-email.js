/*
 * Copyright (c) Zengliwei. All rights reserved.
 * Each source file in this distribution is licensed under OSL 3.0, see LICENSE for details.
 */
define([
    'jquery',
    'Magento_Ui/js/modal/alert',
    'jquery/ui',
    'validation'
], function ($, alert) {
    'use strict';

    $.widget('mage.testEmailConnection', {
        options: {
            url: null,
            button_label: '',
            sending_label: ''
        },
        _create: function () {
            const self = this;
            const $email = self.element.find('input');
            const $button = self.element.find('button');
            const $transportMethod = $('#system_smtp_transport_method');
            const $host = $('#system_smtp_host');
            const $port = $('#system_smtp_port');
            const $authMethod = $('#system_smtp_auth_method');
            const $authProtocol = $('#system_smtp_auth_protocol');
            const $user = $('#system_smtp_smtp_user');
            const $password = $('#system_smtp_smtp_password');

            $button.on('click', function () {
                $email.addClass('required-entry validate-email');
                if ($.validator.validateSingleElement($email)
                    && $.validator.validateSingleElement($host)
                    && $.validator.validateSingleElement($port)
                    && $.validator.validateSingleElement($user)
                    && $.validator.validateSingleElement($password)
                ) {
                    $button.attr('disabled', 'disabled').find('span').text(self.options.sending_label);
                    $.ajax({
                        url: self.options.url,
                        dataType: 'JSON',
                        data: {
                            transport_method: $transportMethod.val(),
                            host: $host.val(),
                            port: $port.val(),
                            auth_method: $authMethod.prop('disabled') ? $authMethod.val() : null,
                            protocol: $authProtocol.prop('disabled') ? $authProtocol.val() : null,
                            user: $user.val(),
                            password: $password.val(),
                            email: $email.val()
                        },
                        success: function (result) {
                            if (!result.success) {
                                alert({content: result.message});
                            } else {
                                alert({content: 'Email sent successfully.'});
                            }
                        },
                        fail: function () {
                            alert({content: 'Failed to send the email, please try again.'});
                        },
                        complete: function () {
                            $button.removeAttr('disabled').find('span').text(self.options.button_label);
                        }
                    });
                }
                $email.removeClass('required-entry validate-email');
            });
        }
    });

    return $.mage.testEmailConnection;
});
