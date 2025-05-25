/**
 * Copyright (c) VCT. All rights reserved
 */
define(['jquery'], function ($) {
    return function () {
        $.validator.addMethod('validate-selector', function (value) {
            try {
                $(value);
                return true;
            } catch (e) { /* eslint-disable-line no-unused-vars */
                return false;
            }
        },

        $.mage.__(`Use <a href="https://api.jquery.com/category/selectors/#content" target="_blank">jQuery</a> valid selector.`)); /* eslint-disable-line max-len */
    };
});
