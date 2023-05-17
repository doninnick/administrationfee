<?php
/**
 * Donin
 *
 * @category Donin
 * @package Donin_AdministrationFee
 */
declare(strict_types=1);

namespace Donin\AdministrationFee\Plugin\QuoteRepository;

use Magento\Quote\Api\Data\CartExtensionFactory;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteRepository\LoadHandler;
use Donin\AdministrationFee\Api\ConfigInterface;
use Donin\AdministrationFee\Api\Data\AdministrationFeeQuoteInterface;
use Donin\AdministrationFee\Api\Data\AdministrationFeeQuoteInterfaceFactory;
use Donin\AdministrationFee\Model\ResourceModel\AdministrationFeeQuote as AdministrationFeeQuoteSource;

class LoadAdministrationFeePlugin
{
    /**
     * @var AdministrationFeeQuoteSource
     */
    private AdministrationFeeQuoteSource $resourceModel;
    /**
     * @var AdministrationFeeQuoteInterfaceFactory
     */
    private AdministrationFeeQuoteInterfaceFactory $baseQuoteFactory;
    /**
     * @var CartExtensionFactory
     */
    private CartExtensionFactory $cartExtensionFactory;
    /**
     * @var ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * LoadAdministrationFeePlugin constructor.
     *
     * @param AdministrationFeeQuoteSource $resourceModel
     * @param AdministrationFeeQuoteInterfaceFactory $baseQuoteFactory
     * @param CartExtensionFactory $cartExtensionFactory
     * @param ConfigInterface $config
     */
    public function __construct(
        AdministrationFeeQuoteSource $resourceModel,
        AdministrationFeeQuoteInterfaceFactory $baseQuoteFactory,
        CartExtensionFactory $cartExtensionFactory,
        ConfigInterface $config
    ) {
        $this->resourceModel = $resourceModel;
        $this->baseQuoteFactory = $baseQuoteFactory;
        $this->cartExtensionFactory = $cartExtensionFactory;
        $this->config = $config;
    }

    /**
     * @param LoadHandler $subject
     * @param Quote|CartInterface $quote
     *
     * @return Quote|CartInterface
     */
    public function afterLoad(LoadHandler $subject, CartInterface $quote): CartInterface
    {
        if (!$quote->getIsActive() || !$this->config->isEnabled()) {
            return $quote;
        }

        $cartExtension = $quote->getExtensionAttributes();
        if ($cartExtension === null) {
            $cartExtension = $this->cartExtensionFactory->create();
        }

        /** @var AdministrationFeeQuoteInterface $administrationFeeQuoteModel */
        $administrationFeeQuoteModel = $this->baseQuoteFactory->create();
        $this->resourceModel->load(
            $administrationFeeQuoteModel,
            $quote->getId(),
            AdministrationFeeQuoteInterface::QUOTE_ID
        );

        if ($administrationFeeQuoteModel->getEntityId()) {
            $quote->setData('pc_administration_fee', $administrationFeeQuoteModel->getAmount());
        }

        return $quote;
    }
}
