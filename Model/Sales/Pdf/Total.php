<?php
/**
 * Donin
 *
 * @category Donin
 * @package Donin_AdministrationFee
 */ 
declare(strict_types=1);

namespace Donin\AdministrationFee\Model\Sales\Pdf;

use Magento\Sales\Model\Order\Pdf\Total\DefaultTotal;
use Magento\Tax\Helper\Data as TaxHelper;
use Magento\Tax\Model\Calculation;
use Magento\Tax\Model\ResourceModel\Sales\Order\Tax\CollectionFactory;
use Donin\AdministrationFee\Api\ConfigInterface;
use Donin\AdministrationFee\Api\Data\AdministrationFeeOrderInterface;
use Donin\AdministrationFee\Api\Data\AdministrationFeeOrderInterfaceFactory;
use Donin\AdministrationFee\Model\ResourceModel\AdministrationFeeOrder as AdministrationFeeOrderSource;

class Total extends DefaultTotal
{
    /**
     * @var ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * @var AdministrationFeeOrderInterfaceFactory
     */
    private AdministrationFeeOrderInterfaceFactory $feeOrderInterfaceFactory;

    /**
     * @var AdministrationFeeOrderSource
     */
    private AdministrationFeeOrderSource $resourceModel;

    /**
     * @param TaxHelper $taxHelper
     * @param Calculation $taxCalculation
     * @param CollectionFactory $ordersFactory
     * @param ConfigInterface $config
     * @param AdministrationFeeOrderInterfaceFactory $feeOrderInterfaceFactory
     * @param AdministrationFeeOrderSource $resourceModel
     * @param array $data
     */
    public function __construct(
        TaxHelper         $taxHelper,
        Calculation       $taxCalculation,
        CollectionFactory $ordersFactory,
        ConfigInterface $config,
        AdministrationFeeOrderInterfaceFactory $feeOrderInterfaceFactory,
        AdministrationFeeOrderSource $resourceModel,
        array             $data = []
    ) {
        $this->config = $config;
        $this->feeOrderInterfaceFactory = $feeOrderInterfaceFactory;
        $this->resourceModel = $resourceModel;
        parent::__construct($taxHelper, $taxCalculation, $ordersFactory, $data);
    }

    /**
     * Check if administration fee total amount should be included
     *
     * Example:
     * array(
     *  $index => array(
     *      'amount'   => $amount,
     *      'label'    => $label,
     *      'font_size'=> $font_size
     *  )
     * )
     *
     * @return array
     */
    public function getTotalsForDisplay()
    {
        if (!$this->config->isEnabled()) {
            return [];
        }

        $order = $this->getOrder();
        /** @var AdministrationFeeOrderInterface $orderInterfaceFactory */
        $feeOrderModel = $this->feeOrderInterfaceFactory->create();
        $this->resourceModel->load($feeOrderModel, $order->getEntityId(), AdministrationFeeOrderInterface::ORDER_ID);

        if (!$feeOrderModel->getEntityId()) {
            return [];
        }

        $fontSize = $this->getFontSize() ? $this->getFontSize() : 7;
        $calculatedAmount = $this->getOrder()->formatPriceTxt($feeOrderModel->getAmount());
        $totals = [
            [
                'amount' => $calculatedAmount,
                'label' => $this->config->getLabel() . ':',
                'font_size' => $fontSize,
            ],
        ];
        return $totals;
    }
}
