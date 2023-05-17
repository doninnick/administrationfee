<?php
/**
 * Donin
 *
 * @category Donin
 * @package Donin_AdministrationFee
 */ 
declare(strict_types=1);

namespace Donin\AdministrationFee\Model\Checkout\Summary\Calculation;

use Magento\Quote\Api\Data\ShippingAssignmentInterface as ShippingAssignment;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total;
use Donin\AdministrationFee\Api\ConfigInterface;

class PercentagePrice implements CalculationInterface
{
    /**
     * @var ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function calculate(Quote $quote, ShippingAssignment $shippingAssignment, Total $total): float
    {
        $amount = $this->config->getAdminFeeAmount();
        $subtotal = $quote->getSubtotal();
        $calculatedAmount = ($amount / 100) * $subtotal;

        return (float) $calculatedAmount;
    }

}
