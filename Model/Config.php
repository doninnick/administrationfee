<?php
/**
 * Donin
 *
 * @category Donin
 * @package Donin_AdministrationFee
 */ 
declare(strict_types=1);

namespace Donin\AdministrationFee\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Donin\AdministrationFee\Api\ConfigInterface;

/**
 * Class Config
 * Provides configuration
 */
class Config implements ConfigInterface
{
    /**
     * XML Path to get access hash
     *
     * @var string
     */
    private const XML_PATH_IS_ENABLED = 'donin_adminfee/general/enable';
    private const XML_PATH_SHIPPING_COUNTRIES_LIST = 'donin_adminfee/general/shipping_countries';
    private const XML_PATH_TYPE = 'donin_adminfee/general/type';
    private const XML_PATH_AMOUNT = 'donin_adminfee/general/amount';
    private const XML_PATH_TITLE = 'donin_adminfee/general/label';

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * Config constructor.
     * @param ScopeConfigInterface $storeConfig
     */
    public function __construct(
        ScopeConfigInterface $storeConfig
    ) {
        $this->scopeConfig = $storeConfig;
    }

    /**
     * Check if Administration Fee extension is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (bool)$this->scopeConfig->isSetFlag(self::XML_PATH_IS_ENABLED, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get Administration Fee type
     *
     * @return string
     */
    public function getAdminFeeType(): string
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_TYPE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get Administration Fee Amount
     *
     * @return float
     */
    public function getAdminFeeAmount(): float
    {
        return (float)$this->scopeConfig->getValue(self::XML_PATH_AMOUNT, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Returns array of the Shipping country codes
     *
     * @return array
     */
    public function getShippingCountries(): array
    {
        return (array)explode(
            ',',
            $this->scopeConfig->getValue(self::XML_PATH_SHIPPING_COUNTRIES_LIST, ScopeInterface::SCOPE_STORE)
        );
    }

    /**
     * @return Phrase
     */
    public function getLabel(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_TITLE, ScopeInterface::SCOPE_STORE);
    }
}
