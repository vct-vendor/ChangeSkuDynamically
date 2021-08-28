/**
 * Copyright (c) VCT. All rights reserved
 */
define(['jquery'], function ($) {
    'use strict';

    return {
        switch: function (config, productId) {
            var moduleConfig = config['vct_changeskudynamically'];

            if (productId !== undefined && moduleConfig['general']['switch_sku'] === true &&
                moduleConfig['data']['full_action_name'] === 'catalog_product_view') {
                $(moduleConfig['general']['sku_selector']).html(moduleConfig['data']['skus'][productId]);
            }
        }
    };
});
