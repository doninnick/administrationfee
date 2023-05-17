<?php
/**
 * Donin
 *
 * @category Donin
 * @package Donin_AdministrationFee
 */
declare(strict_types=1);

namespace Donin\AdministrationFee\Observer\QuoteSubmit;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Quote\Model\Quote;
use Magento\Sales\Model\Order;
use Donin\AdministrationFee\Api\ConfigInterface;
use Donin\AdministrationFee\Api\Data\AdministrationFeeOrderInterface;
use Donin\AdministrationFee\Api\Data\AdministrationFeeOrderInterfaceFactory;
use Donin\AdministrationFee\Model\ResourceModel\AdministrationFeeOrder as AdministrationFeeOrderSource;

class OrderSaveExtensionAttributesObserver implements ObserverInterface
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
     */
    public function __construct(
        ConfigInterface $config,
        AdministrationFeeOrderInterfaceFactory $feeOrderInterfaceFactory,
        AdministrationFeeOrderSource $resourceModel
    ) {
        $this->config = $config;
        $this->feeOrderInterfaceFactory = $feeOrderInterfaceFactory;
        $this->resourceModel = $resourceModel;
    }

    /**
     * @param EventObserver $observer
     *
     * @return $this|void
     *
     * @throws AlreadyExistsException
     */
    public function execute(EventObserver $observer)
    {
        if (!$this->config->isEnabled()) {
            return $this;
        }

        /** @var  Quote $quote */
        $quote = $observer->getEvent()->getQuote();
        /** @var  Order $order */
        $order = $observer->getEvent()->getOrder();

        $totals = $quote->getTotals();
        $amount = isset($totals['pc_administration_fee'])
            ? $totals['pc_administration_fee']->getValue()
            : $quote->getData('pc_administration_fee');
        if ($amount) {
            /** @var AdministrationFeeOrderInterface $orderInterfaceFactory */
            $feeOrderModel = $this->feeOrderInterfaceFactory->create();
            $this->resourceModel->load($feeOrderModel, $order->getId(), AdministrationFeeOrderInterface::ORDER_ID);
            $feeOrderModel->setOrderId((int) $order->getId());
            $feeOrderModel->setAmount($amount);
            $this->resourceModel->save($feeOrderModel);
        }

        return $this;
    }
}
