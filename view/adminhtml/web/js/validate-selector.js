/**
 * Copyright (c) VCT. All rights reserved
 */
define(['jquery'], function ($) {
    'use strict';

    return function () {
        $.validator.addMethod(
            'validate-selector',
            function (value) {
                try {
                    $(value);
                } catch (e) {
                    return false;
                }
                return true;
            },
            $.mage.__('Use a valid <a href="https://api.jquery.com/category/selectors/#content" ' +
                      'target="_blank">jQuery</a> selector.')
        );
    };
});
