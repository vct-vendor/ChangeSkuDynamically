/**
 * Copyright (c) VCT. All rights reserved
 */
define(['jquery'], function ($) {
    return {
        /**
         * @param {array} config
         * @param {number} productId
         */
        switch: function (config, productId) {
            if (!config?.['vct_changeskudynamically']) {
                return;
            }

            const moduleConfig = config['vct_changeskudynamically'],
                generalConfig = moduleConfig['config']['general'];

            if (productId !== undefined && generalConfig['switch_sku'] === true) {
                $(generalConfig['sku_selector']).html(moduleConfig['data']['skus'][productId]);
            }
        }
    };
});
