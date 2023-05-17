<?php
/**
 * Donin
 *
 * @category Donin
 * @package Donin_AdministrationFee
 */
declare(strict_types=1);

namespace Donin\AdministrationFee\Plugin;

use Magento\Checkout\Block\Checkout\LayoutProcessor;
use Donin\AdministrationFee\Api\ConfigInterface;

class CheckoutLayoutProcessorPlugin
{
    /**
     * @var ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * @param ConfigInterface $config
     */
    public function __construct(
        ConfigInterface $config
    ) {
        $this->config = $config;
    }

    /**
     * @param LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(
        LayoutProcessor $subject,
        array $jsLayout
    ) {
        if (isset($jsLayout['components']['checkout']['children']['sidebar']['children']
            ['summary']['children']['totals']['children']['administration-fee']['config'])) {
            $jsLayout['components']['checkout']['children']['sidebar']['children']
            ['summary']['children']['totals']['children']['administration-fee']['config']['title'] = $this->config->getLabel();
        }

        return $jsLayout;
    }
}
