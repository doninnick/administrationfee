<?php
/**
 * Donin
 *
 * @category Donin
 * @package Donin_AdministrationFee
 */ 
declare(strict_types=1);

namespace Donin\AdministrationFee\Model\Checkout\Summary;

use Magento\Quote\Api\Data\ShippingAssignmentInterface as ShippingAssignment;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Magento\SalesRule\Model\Quote\Address\Total\ShippingDiscount;
use Donin\AdministrationFee\Api\ConfigInterface;
use Donin\AdministrationFee\Model\Config\TypePool;

class TotalSummary extends AbstractTotal
{
    /**
     * @var ConfigInterface
     */
    private ConfigInterface $config;
    /**
     * @var TypePool
     */
    private TypePool $typePool;

    /**
     * @var float
     */
    private float $feeAmount = 0.0;
    /**
     * @var bool
     */
    private bool $fetchFee = false;

    /**
     * @param ConfigInterface $config
     * @param TypePool $typePool
     */
    public function __construct(
        ConfigInterface $config,
        TypePool $typePool
    ) {
        $this->config = $config;
        $this->typePool = $typePool;
        $this->setCode('pc_administration_fee');
    }

    /**
     * @inheritdoc
     *
     * @param Quote $quote
     * @param ShippingAssignment $shippingAssignment
     * @param Total $total
     * @return ShippingDiscount
     */
    public function collect(Quote $quote, ShippingAssignment $shippingAssignment, Total $total): self
    {
        $quote->setData($this->getCode(), 0.0);
        if (!$this->config->isEnabled()) {
            return $this;
        }
        $items = $shippingAssignment->getItems();
        if (!$items) {
            return $this;
        }

        if (!in_array($quote->getShippingAddress()->getCountryId(), $this->config->getShippingCountries())) {
            return $this;
        }

        $this->fetchFee = true;
        parent::collect($quote, $shippingAssignment, $total);

        $type = $this->config->getAdminFeeType();
        $amountCalculator = $this->typePool->getAmountCalculator($type);
        $calculatedAmount = $amountCalculator->calculate($quote, $shippingAssignment, $total);

        $total->setTotalAmount($this->getCode(), $calculatedAmount);
        $total->setBaseTotalAmount($this->getCode(), $calculatedAmount);

        // saving the data for the quote save in extension attribute.
        $quote->setData($this->getCode(), $calculatedAmount);
        $this->feeAmount = $calculatedAmount;

        return $this;
    }

    /**
     * @param Quote $quote
     * @param Total $total
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function fetch(Quote $quote, Total $total): array
    {
        if (!$this->fetchFee) {
            return [];
        }
        return [
            'code' => $this->getCode(),
            'title' => $this->config->getLabel(),
            'value' => $this->feeAmount,
            'area' => 'footer'
        ];
    }
}
