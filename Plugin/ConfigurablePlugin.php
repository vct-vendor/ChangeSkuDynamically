<?php
/**
 * Copyright (c) VCT. All rights reserved
 */
declare(strict_types=1);

namespace Vct\ChangeSkuDynamically\Plugin;

use Magento\ConfigurableProduct\Block\Product\View\Type\Configurable;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Magento\Store\Model\ScopeInterface;

/**
 * @copyright Copyright (c) VCT
 * @link https://vct-vendor.github.io
 */
class ConfigurablePlugin
{
    public const GENERAL_SWITCH_SKU = 'vct_changeskudynamically/general/switch_sku';
    public const GENERAL_SKU_SELECTOR = 'vct_changeskudynamically/general/sku_selector';
    public const PRODUCT_VIEW = 'catalog_product_view';

    /**
     * @var JsonSerializer
     */
    private JsonSerializer $jsonSerializer;

    /**
     * @var HttpRequest
     */
    private HttpRequest $httpRequest;

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @param JsonSerializer $jsonSerializer
     * @param HttpRequest $httpRequest
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        JsonSerializer $jsonSerializer,
        HttpRequest $httpRequest,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->jsonSerializer = $jsonSerializer;
        $this->httpRequest = $httpRequest;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Add a config to switch SKU
     *
     * @param Configurable $subject
     * @param string $result
     * @return string
     */
    public function afterGetJsonConfig(Configurable $subject, string $result): string
    {
        $jsonConfig = (array)$this->jsonSerializer->unserialize($result);
        $moduleConfig = &$jsonConfig['vct_changeskudynamically'];
        $moduleConfig['data']['full_action_name'] = $this->httpRequest->getFullActionName();
        $moduleConfig['general']['switch_sku'] = (bool)$this->scopeConfig->getValue(
            self::GENERAL_SWITCH_SKU,
            ScopeInterface::SCOPE_STORE
        );

        if ($moduleConfig['data']['full_action_name'] === self::PRODUCT_VIEW
            && $moduleConfig['general']['switch_sku']) {
            $moduleConfig['general']['sku_selector'] = $this->scopeConfig->getValue(
                self::GENERAL_SKU_SELECTOR,
                ScopeInterface::SCOPE_STORE
            );
            $parent = $subject->getProduct();
            $moduleConfig['data']['skus']['parent'][$parent->getId()] = $parent->getSku();

            foreach ($subject->getAllowProducts() as $child) {
                $moduleConfig['data']['skus'][$child->getId()] = $child->getSku();
            }
        }

        return $this->jsonSerializer->serialize($jsonConfig);
    }
}
