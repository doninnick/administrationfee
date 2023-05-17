<?php
/**
 * Donin
 *
 * @category Donin
 * @package Donin_AdministrationFee
 */
declare(strict_types=1);

namespace Donin\AdministrationFee\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\Quote;
use Magento\Sales\Api\Data\OrderInterface;
use Donin\AdministrationFee\Api\ConfigInterface;
use Donin\AdministrationFee\Api\Data\AdministrationFeeQuoteInterface;
use Donin\AdministrationFee\Api\Data\AdministrationFeeQuoteInterfaceFactory;
use Donin\AdministrationFee\Model\ResourceModel\AdministrationFeeQuote as AdministrationFeeQuoteSource;
use Psr\Log\LoggerInterface;

class ValidateExtensionAttributesBeforeOrderObserver implements ObserverInterface
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
    private AdministrationFeeQuoteInterfaceFactory $baseQuoteFactory;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    public function __construct(
        ConfigInterface $config,
        AdministrationFeeQuoteSource $resourceModel,
        AdministrationFeeQuoteInterfaceFactory $baseQuoteFactory,
        LoggerInterface $logger
    ) {
        $this->config = $config;
        $this->resourceModel = $resourceModel;
        $this->baseQuoteFactory = $baseQuoteFactory;
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        if (!$this->config->isEnabled()) {
            return $this;
        }

        /** @var  Quote $quote */
        $quote = $observer->getEvent()->getQuote();
        /** @var OrderInterface $order */
        $order = $observer->getEvent()->getOrder();
        if ($quote) {
            /** @var AdministrationFeeQuoteInterface $feeQuoteModel */
            $feeQuoteModel = $this->baseQuoteFactory->create();
            $this->resourceModel->load($feeQuoteModel, $quote->getId(), AdministrationFeeQuoteInterface::QUOTE_ID);

            if ($feeQuoteModel->getEntityId() && !in_array($quote->getShippingAddress()->getCountryId(), $this->config->getShippingCountries())) {
                try {
                    $quote->setData('pc_administration_fee', null);
                    $this->resourceModel->delete($feeQuoteModel);
                    $this->logger->info(
                        sprintf(
                            "AdministrationFee Quote Before Submit: Deleting attached to quote administration amount attributes (Entity_ID: %s): ",
                            $feeQuoteModel->getEntityId()
                        )
                    );
                } catch (\Exception $exception) {
                    $this->logger->error("AdministrationFee Order: Error validating extension Attributes: " . $exception->getMessage());
                }
            } elseif ($feeQuoteModel->getEntityId()) {
                $quote->setData('pc_administration_fee', $feeQuoteModel->getAmount());
                $order->setData('pc_administration_fee', $feeQuoteModel->getAmount());
            }
        }
    }
}
