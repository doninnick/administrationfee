<?php
/**
 * Donin
 *
 * @category Donin
 * @package Donin_AdministrationFee
 */ 
declare(strict_types=1);

namespace Donin\AdministrationFee\Api\Data;

interface AdministrationFeeOrderInterface
{
    const ENTITY_ID = 'entity_id';
    const ORDER_ID = 'order_id';
    const AMOUNT = 'amount';

    /**
     * @return int
     */
    public function getEntityId(): int;

    /**
     * @return int
     */
    public function getOrderId(): int;

    /**
     * @param int $orderId
     * @return void
     */
    public function setOrderId(int $orderId);

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
