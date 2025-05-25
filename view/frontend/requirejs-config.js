/**
 * Copyright (c) VCT. All rights reserved
 */
const config = {
    config: {
        mixins: {
            'Magento_ConfigurableProduct/js/configurable': {
                'Vct_ChangeSkuDynamically/js/configurable': true
            },
            'Magento_Swatches/js/swatch-renderer': {
                'Vct_ChangeSkuDynamically/js/swatch-renderer': true
            }
        }
    }
};
