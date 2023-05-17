<?php
/**
 * Donin
 *
 * @category Donin
 * @package Donin_AdministrationFee
 */ 
declare(strict_types=1);

namespace Donin\AdministrationFee\Model\Total\Invoice;

use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;
use Donin\AdministrationFee\Api\ConfigInterface;
use Donin\AdministrationFee\Api\Data\AdministrationFeeOrderInterface;
use Donin\AdministrationFee\Api\Data\AdministrationFeeOrderInterfaceFactory;
use Donin\AdministrationFee\Model\ResourceModel\AdministrationFeeOrder as AdministrationFeeOrderSource;
use Donin\AdministrationFee\Api\Data\AdministrationFeeQuoteInterface;
use Donin\AdministrationFee\Api\Data\AdministrationFeeQuoteInterfaceFactory;
use Donin\AdministrationFee\Model\ResourceModel\AdministrationFeeQuote as AdministrationFeeQuoteSource;

class TotalSummary extends AbstractTotal
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
     * @var AdministrationFeeQuoteInterfaceFactory
     */
    private AdministrationFeeQuoteInterfaceFactory $feeQuoteInterfaceFactory;

    /**
     * @var AdministrationFeeQuoteSource
     */
    private AdministrationFeeQuoteSource $resourceQuote;

    /**
     * @param ConfigInterface $config
     * @param AdministrationFeeOrderInterfaceFactory $feeOrderInterfaceFactory
     * @param AdministrationFeeOrderSource $resourceModel
     * @param AdministrationFeeQuoteInterfaceFactory $feeQuoteInterfaceFactory
     * @param AdministrationFeeQuoteSource $resourceQuote
     * @param array $data
     */
    public function __construct(
        ConfigInterface $config,
        AdministrationFeeOrderInterfaceFactory $feeOrderInterfaceFactory,
        AdministrationFeeOrderSource $resourceModel,
        AdministrationFeeQuoteInterfaceFactory $feeQuoteInterfaceFactory,
        AdministrationFeeQuoteSource $resourceQuote,
        array $data = []
    ) {
        parent::__construct($data);
        $this->config = $config;
        $this->feeOrderInterfaceFactory = $feeOrderInterfaceFactory;
        $this->resourceModel = $resourceModel;
        $this->feeQuoteInterfaceFactory = $feeQuoteInterfaceFactory;
        $this->resourceQuote = $resourceQuote;
        $this->setCode('pc_administration_fee');
    }

    /**
     * Collect invoice subtotal
     *
     * @param Invoice $invoice
     * @return $this|TotalSummary
     */
    public function collect(Invoice $invoice)
    {
        if (!$this->config->isEnabled()) {
            return $this;
        }

        /** @var Order $order */
        $order = $invoice->getOrder();
        /** @var AdministrationFeeOrderInterface $orderInterfaceFactory */
//        $feeOrderModel = $this->feeOrderInterfaceFactory->create();
//        $this->resourceModel->load($feeOrderModel, $order->getEntityId(), AdministrationFeeOrderInterface::ORDER_ID);

        /** @var AdministrationFeeQuoteInterface $feeQuoteModel */
        $feeQuoteModel = $this->feeQuoteInterfaceFactory->create();
        $this->resourceQuote->load($feeQuoteModel, $order->getQuoteId(), AdministrationFeeQuoteInterface::QUOTE_ID);

        if (!$feeQuoteModel->getEntityId()) {
            return $this;
        }

        $calculatedAmount = $feeQuoteModel->getAmount();
        $invoice->setData($this->getCode(), $calculatedAmount);

        $invoice->setGrandTotal($invoice->getGrandTotal() + $calculatedAmount);
        $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $calculatedAmount);
        return $this;
    }
}
