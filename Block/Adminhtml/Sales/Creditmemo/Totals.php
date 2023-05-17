<?php
/**
 * Donin
 *
 * @category Donin
 * @package Donin_AdministrationFee
 */ 
declare(strict_types=1);

namespace Donin\AdministrationFee\Block\Adminhtml\Sales\Creditmemo;

use Magento\Framework\View\Element\Template;
use Magento\Sales\Model\Order\Creditmemo;
use Donin\AdministrationFee\Api\ConfigInterface;
use Donin\AdministrationFee\Api\Data\AdministrationFeeOrderInterface;
use Donin\AdministrationFee\Api\Data\AdministrationFeeOrderInterfaceFactory;
use Donin\AdministrationFee\Model\ResourceModel\AdministrationFeeOrder as AdministrationFeeOrderSource;

class Totals extends Template
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
     * @param Template\Context $context
     * @param ConfigInterface $config
     * @param AdministrationFeeOrderInterfaceFactory $feeOrderInterfaceFactory
     * @param AdministrationFeeOrderSource $resourceModel
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ConfigInterface $config,
        AdministrationFeeOrderInterfaceFactory $feeOrderInterfaceFactory,
        AdministrationFeeOrderSource $resourceModel,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->config = $config;
        $this->feeOrderInterfaceFactory = $feeOrderInterfaceFactory;
        $this->resourceModel = $resourceModel;
    }

    /**
     * @return Creditmemo
     */
    public function getCreditmemo() {
        return $this->getParentBlock()->getCreditmemo();
    }

    /**
     * Create Administration Fee totals summary
     *
     * @return $this
     */
    public function initTotals()
    {
        if (!$this->config->isEnabled()) {
            return $this;
        }

        /** @var Order $order */
        $order = $this->getCreditmemo()->getOrder();
        /** @var AdministrationFeeOrderInterface $feeOrderModel */
        $feeOrderModel = $this->feeOrderInterfaceFactory->create();
        $this->resourceModel->load($feeOrderModel, $order->getEntityId(), AdministrationFeeOrderInterface::ORDER_ID);

        if (!$feeOrderModel->getEntityId()) {
            return $this;
        }

        $calculatedAmount = $feeOrderModel->getAmount();

        // Add our total information to the set of other totals
        $total = new \Magento\Framework\DataObject(
            [
                'code' => $this->getNameInLayout(),
                'label' => $this->config->getLabel(),
                'value' => $calculatedAmount,
                'base_value' => $calculatedAmount
            ]
        );

        $this->getParentBlock()->addTotalBefore($total, 'grand_total');

        return $this;
    }
}
