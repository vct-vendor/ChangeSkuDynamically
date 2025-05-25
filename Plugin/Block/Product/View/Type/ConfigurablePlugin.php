<?php
/**
 * Copyright (c) VCT. All rights reserved
 */
declare(strict_types=1);

namespace Vct\ChangeSkuDynamically\Plugin\Block\Product\View\Type;

use Magento\ConfigurableProduct\Block\Product\View\Type\Configurable;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Magento\Store\Model\ScopeInterface;

/**
 * @copyright Copyright (c) VCT. All rights reserved
 * @link https://vct-vendor.github.io
 * @phpcs:ignoreFile Magento2.Annotation.MethodAnnotationStructure.MethodAnnotation
 */
class ConfigurablePlugin
{
    public const CONFIG_GENERAL_SKU_SELECTOR = 'vct_changeskudynamically/general/sku_selector';
    public const CONFIG_GENERAL_SWITCH_SKU = 'vct_changeskudynamically/general/switch_sku';

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
     * @param Configurable $subject
     * @param string $result
     * @return string
     */
    public function afterGetJsonConfig(Configurable $subject, string $result): string
    {
        if ($this->httpRequest->getFullActionName() !== 'catalog_product_view') {
            return $result;
        }

        $jsonConfig = (array)$this->jsonSerializer->unserialize($result);
        $moduleConfig = &$jsonConfig['vct_changeskudynamically'];
        $moduleGeneralConfig = &$moduleConfig['config']['general'];
        $moduleDataSkus = &$moduleConfig['data']['skus'];
        $moduleGeneralConfig['switch_sku'] = (bool)$this->scopeConfig->getValue(
            self::CONFIG_GENERAL_SWITCH_SKU,
            ScopeInterface::SCOPE_STORE,
        );

        if ($moduleGeneralConfig['switch_sku']) {
            $moduleGeneralConfig['sku_selector'] = $this->scopeConfig->getValue(
                self::CONFIG_GENERAL_SKU_SELECTOR,
                ScopeInterface::SCOPE_STORE,
            );
            $parent = $subject->getProduct();
            $moduleDataSkus['parent'][$parent->getId()] = $parent->getSku();

            foreach ($subject->getAllowProducts() as $child) {
                $moduleDataSkus[$child->getId()] = $child->getSku();
            }
        }

        return $this->jsonSerializer->serialize($jsonConfig);
    }
}
