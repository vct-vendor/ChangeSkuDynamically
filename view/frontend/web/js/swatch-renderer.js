/**
 * Copyright (c) VCT. All rights reserved
 */
define(['jquery', 'Vct_ChangeSkuDynamically/js/switcher'], function ($, switcher) {
    return function (widget) {
        $.widget('mage.SwatchRenderer', widget, {
            _UpdatePrice: function () {
                switcher.switch(this.options.jsonConfig, this.getProduct());
                this._super();
            }
        });

        return $.mage.SwatchRenderer;
    };
});
