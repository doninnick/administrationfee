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

interface CalculationInterface
{
    /**
     * @param Quote $quote
     * @param ShippingAssignment $shippingAssignment
     * @param Total $total
     * @return float
     */
    public function calculate(
        Quote $quote,
        ShippingAssignment $shippingAssignment,
        Total $total
    ): float;
}
