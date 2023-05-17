<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace PronkoConsulting\AdministrationFee\Model\Total\Creditmemo;

use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal;
use PronkoConsulting\AdministrationFee\Api\ConfigInterface;
use PronkoConsulting\AdministrationFee\Api\Data\AdministrationFeeOrderInterface;
use PronkoConsulting\AdministrationFee\Api\Data\AdministrationFeeOrderInterfaceFactory;
use PronkoConsulting\AdministrationFee\Model\ResourceModel\AdministrationFeeOrder as AdministrationFeeOrderSource;

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
     * @param ConfigInterface $config
     * @param AdministrationFeeOrderInterfaceFactory $feeOrderInterfaceFactory
     * @param AdministrationFeeOrderSource $resourceModel
     * @param array $data
     */
    public function __construct(
        ConfigInterface $config,
        AdministrationFeeOrderInterfaceFactory $feeOrderInterfaceFactory,
        AdministrationFeeOrderSource $resourceModel,
        array $data = []
    ) {
        parent::__construct($data);
        $this->config = $config;
        $this->feeOrderInterfaceFactory = $feeOrderInterfaceFactory;
        $this->resourceModel = $resourceModel;
        $this->setCode('pc_administration_fee');
    }

    /**
     * Collect creditmemo subtotal
     *
     * @param Creditmemo $creditmemo
     * @return $this|TotalSummary
     */
    public function collect(Creditmemo $creditmemo)
    {
        if (!$this->config->isEnabled()) {
            return $this;
        }

        /** @var Order $order */
        $order = $creditmemo->getOrder();
        /** @var AdministrationFeeOrderInterface $orderInterfaceFactory */
        $feeOrderModel = $this->feeOrderInterfaceFactory->create();
        $this->resourceModel->load($feeOrderModel, $order->getEntityId(), AdministrationFeeOrderInterface::ORDER_ID);

        if (!$feeOrderModel->getEntityId()) {
            return $this;
        }

        $calculatedAmount = $feeOrderModel->getAmount();
        $creditmemo->setData($this->getCode(), $calculatedAmount);

        $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $calculatedAmount);
        $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $calculatedAmount);

        return $this;
    }
}

