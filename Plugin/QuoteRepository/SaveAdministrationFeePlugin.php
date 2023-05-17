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
use Magento\Quote\Model\QuoteRepository\SaveHandler;
use Donin\AdministrationFee\Api\ConfigInterface;
use Donin\AdministrationFee\Api\Data\AdministrationFeeQuoteInterface;
use Donin\AdministrationFee\Api\Data\AdministrationFeeQuoteInterfaceFactory;
use Donin\AdministrationFee\Model\ResourceModel\AdministrationFeeQuote as AdministrationFeeQuoteSource;

class SaveAdministrationFeePlugin
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
     * Save Quote Custom Administration Fee Attribute.
     *
     * @param SaveHandler $subject
     * @param CartInterface $quote
     *
     * @return CartInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSave(SaveHandler $subject, CartInterface $quote): CartInterface
    {
        if (!$quote->getIsActive() || !$this->config->isEnabled()) {
            return $quote;
        }

        $totals = $quote->getTotals();
        $amount = isset($totals['pc_administration_fee'])
            ? $totals['pc_administration_fee']->getValue()
            : $quote->getData('pc_administration_fee');
        if ($amount) {
            /** @var AdministrationFeeQuoteInterface $administrationFeeQuoteModel */
            $administrationFeeQuoteModel = $this->baseQuoteFactory->create();
            $this->resourceModel->load(
                $administrationFeeQuoteModel,
                $quote->getId(),
                AdministrationFeeQuoteInterface::QUOTE_ID
            );

            $administrationFeeQuoteModel->setQuoteId((int) $quote->getId());
            $administrationFeeQuoteModel->setAmount($amount);
            $this->resourceModel->save($administrationFeeQuoteModel);
        }

        return $quote;
    }
}
