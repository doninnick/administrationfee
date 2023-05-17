<?php
/**
 * Donin
 *
 * @category Donin
 * @package Donin_AdministrationFee
 */ 
declare(strict_types=1);

namespace Donin\AdministrationFee\Api\Data;

interface AdministrationFeeQuoteInterface
{
    const ENTITY_ID = 'entity_id';
    const QUOTE_ID = 'quote_id';
    const AMOUNT = 'amount';

    /**
     * @return int
     */
    public function getEntityId(): int;

    /**
     * @return int
     */
    public function getQuoteId(): int;

    /**
     * @param int $quoteId
     * @return void
     */
    public function setQuoteId(int $quoteId);

    /**
     * @return float
     */
    public function getAmount(): float;

    /**
     * @param float $value
     * @return float
     */
    public function setAmount(float $value);
}
