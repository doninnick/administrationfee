<?php
/**
 * Donin
 *
 * @category Donin
 * @package Donin_AdministrationFee
 */ 
declare(strict_types=1);

namespace Donin\AdministrationFee\Observer\Payment;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Payment\Model\Cart;
use Donin\AdministrationFee\Api\ConfigInterface;
use Donin\AdministrationFee\Api\Data\AdministrationFeeQuoteInterface;
use Donin\AdministrationFee\Api\Data\AdministrationFeeQuoteInterfaceFactory;
use Donin\AdministrationFee\Model\ResourceModel\AdministrationFeeQuote as AdministrationFeeQuoteSource;

class CollectTotalsAndAmounts implements ObserverInterface
{
    /**
     * @var ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * @var AdministrationFeeQuoteSource
     */
    private AdministrationFeeQuoteSource $resourceModel;

    /**
     * @var AdministrationFeeQuoteInterfaceFactory
     */
    private AdministrationFeeQuoteInterfaceFactory $feeQuoteFactory;

    /**
     * @param ConfigInterface $config
     * @param AdministrationFeeQuoteSource $resourceModel
     * @param AdministrationFeeQuoteInterfaceFactory $feeQuoteFactory
     */
    public function __construct(
        ConfigInterface $config,
        AdministrationFeeQuoteSource $resourceModel,
        AdministrationFeeQuoteInterfaceFactory $feeQuoteFactory
    ) {
        $this->config = $config;
        $this->resourceModel = $resourceModel;
        $this->feeQuoteFactory = $feeQuoteFactory;
    }

    public function execute(EventObserver $observer): void
    {
        if ($this->config->isEnabled()) {
            /** @var Cart $cart */
            $cart = $observer->getEvent()->getCart();

            $id = $cart->getSalesModel()->getDataUsingMethod('entity_id');

            if (!$id) {
                $id = $cart->getSalesModel()->getDataUsingMethod('quote_id');
            }

            /** @var AdministrationFeeQuoteInterface $feeQuoteModel */
            $feeQuoteModel = $this->feeQuoteFactory->create();
            $this->resourceModel->load(
                $feeQuoteModel,
                $id,
                AdministrationFeeQuoteInterface::QUOTE_ID
            );

            if ($feeQuoteModel->getEntityId()) {
                $cart->addCustomItem(
                    (string)$this->config->getLabel(),
                    1,
                    $feeQuoteModel->getAmount(),
                    'pc_administration_fee'
                );
            }
        }
    }
}
