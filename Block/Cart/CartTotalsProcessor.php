<?php
/**
 * Donin
 *
 * @category Donin
 * @package Donin_AdministrationFee
 */ 
declare(strict_types=1);

namespace Donin\AdministrationFee\Block\Cart;

use Magento\Checkout\Model\Layout\AbstractTotalsProcessor;
use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Donin\AdministrationFee\Api\ConfigInterface;

class CartTotalsProcessor extends AbstractTotalsProcessor implements LayoutProcessorInterface
{
    /**
     * @var ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ConfigInterface $config
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ConfigInterface $config
    ) {
        parent::__construct($scopeConfig);
        $this->config = $config;
    }

    /**
     * @param $jsLayout
     * @return array
     */
    public function process($jsLayout)
    {
        if ($this->config->isEnabled()) {
            $totals = &$jsLayout['components']['block-totals']['children'];
            if (isset($totals['administration-fee'])) {
                $totals['administration-fee']['config']['title'] = $this->config->getLabel();
            }
        }

        return $jsLayout;
    }
}
