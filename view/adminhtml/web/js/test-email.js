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
            url: null
        },
        _create: function () {
            const self = this;
            const $email = self.element.find('input');
            self.element.find('button').on('click', function () {
                $email.addClass('required-entry validate-email');
                if ($.validator.validateSingleElement($email)) {
                    $.ajax({
                        url: self.options.url,
                        dataType: 'JSON',
                        data: {email: $email.val()},
                        success: function (result) {
                            if (!result.success) {
                                alert({content: result.message});
                            } else {
                                alert({content: 'Email sent successfully.'});
                            }
                        }
                    });
                }
                $email.removeClass('required-entry validate-email');
            });
        }
    });

    return $.mage.testEmailConnection;
});
