<?php
/**
 * Donin
 *
 * @category Donin
 * @package Donin_AdministrationFee
 */
declare(strict_types=1);

namespace Donin\AdministrationFee\Plugin\OrderRepository;

use Magento\Sales\Api\Data\OrderExtension;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\ResourceModel\Order\Collection as OrderCollection;
use Donin\AdministrationFee\Api\ConfigInterface;
use Donin\AdministrationFee\Api\Data\AdministrationFeeOrderInterface;
use Donin\AdministrationFee\Api\Data\AdministrationFeeOrderInterfaceFactory;
use Donin\AdministrationFee\Api\Data\AdministrationFeeQuoteInterface;
use Donin\AdministrationFee\Api\Data\AdministrationFeeQuoteInterfaceFactory;
use Donin\AdministrationFee\Model\ResourceModel\AdministrationFeeOrder as AdministrationFeeOrderSource;
use Donin\AdministrationFee\Model\ResourceModel\AdministrationFeeQuote as AdministrationFeeQuoteSource;

class OrderLoadExtensionAttributePlugin
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
     * @var OrderExtensionFactory
     */
    private OrderExtensionFactory $orderExtensionFactory;

    /**
     * @var AdministrationFeeQuoteSource
     */
    private AdministrationFeeQuoteSource $resourceQuote;

    /**
     * @var AdministrationFeeQuoteInterfaceFactory
     */
    private AdministrationFeeQuoteInterfaceFactory $feeQuoteInterfaceFactory;

    public function __construct(
        ConfigInterface $config,
        AdministrationFeeOrderInterfaceFactory $feeOrderInterfaceFactory,
        AdministrationFeeOrderSource $resourceModel,
        OrderExtensionFactory $orderExtensionFactory,
        AdministrationFeeQuoteSource $resourceQuote,
        AdministrationFeeQuoteInterfaceFactory $feeQuoteInterfaceFactory
    ) {
        $this->config = $config;
        $this->feeOrderInterfaceFactory = $feeOrderInterfaceFactory;
        $this->resourceModel = $resourceModel;
        $this->orderExtensionFactory = $orderExtensionFactory;
        $this->resourceQuote = $resourceQuote;
        $this->feeQuoteInterfaceFactory = $feeQuoteInterfaceFactory;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $resultOrder
     *
     * @return OrderInterface
     */
    public function afterGet(
        OrderRepositoryInterface $subject,
        OrderInterface $resultOrder
    ) {
        if (!$this->config->isEnabled()) {
            return $resultOrder;
        }

        /** @var AdministrationFeeOrderInterface $orderInterfaceFactory */
//        $feeOrderModel = $this->feeOrderInterfaceFactory->create();
//        $this->resourceModel->load($feeOrderModel, $resultOrder->getEntityId(), AdministrationFeeOrderInterface::ORDER_ID);

        /** @var AdministrationFeeQuoteInterface $feeQuoteMode */
        $feeQuoteModel = $this->feeQuoteInterfaceFactory->create();
        $this->resourceQuote->load($feeQuoteModel, $resultOrder->getQuoteId(), AdministrationFeeQuoteInterface::QUOTE_ID);

        if (!$feeQuoteModel->getEntityId()) {
            return $resultOrder;
        }

        $extensionAttributes = $resultOrder->getExtensionAttributes();
        /** @var OrderExtension $orderExtension */
        $orderExtension = $extensionAttributes ?: $this->orderExtensionFactory->create();

        $orderExtension->setAdministrationAmount($feeQuoteModel->getAmount());
        $resultOrder->setExtensionAttributes($orderExtension);

        return $resultOrder;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderCollection $resultOrder
     *
     * @return OrderCollection
     */
    public function afterGetList(
        OrderRepositoryInterface $subject,
        OrderCollection $resultOrder
    ) {
        /** @var  $order */
        foreach ($resultOrder->getItems() as $order) {
            $this->afterGet($subject, $order);
        }

        return $resultOrder;
    }
}
