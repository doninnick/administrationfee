<?php
/**
 * Donin
 *
 * @category Donin
 * @package Donin_AdministrationFee
 */ 
declare(strict_types=1);

namespace Donin\AdministrationFee\Model;

use Magento\Framework\Model\AbstractModel;
use Donin\AdministrationFee\Api\Data\AdministrationFeeQuoteInterface;

class AdministrationFeeQuote extends AbstractModel implements AdministrationFeeQuoteInterface
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->_init(ResourceModel\AdministrationFeeQuote::class);
        $this->setIdFieldName(AdministrationFeeQuoteInterface::ENTITY_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityId(): int
    {
        return (int) $this->getData(self::ENTITY_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function getQuoteId(): int
    {
        return (int) $this->getData(self::QUOTE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setQuoteId(int $quoteId)
    {
        $this->setData(self::QUOTE_ID, $quoteId);
    }

    /**
     * {@inheritdoc}
     */
    public function getAmount(): float
    {
        return (float) $this->getData(self::AMOUNT);
    }

    /**
     * {@inheritdoc}
     */
    public function setAmount(float $value)
    {
        $this->setData(self::AMOUNT, $value);
    }
}
