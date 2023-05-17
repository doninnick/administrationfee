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
use Donin\AdministrationFee\Model\ResourceModel\AdministrationFeeOrder as ResourceModel;
use Donin\AdministrationFee\Api\Data\AdministrationFeeOrderInterface;

class AdministrationFeeOrder extends AbstractModel implements AdministrationFeeOrderInterface
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
        $this->setIdFieldName(AdministrationFeeOrderInterface::ENTITY_ID);
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
    public function getOrderId(): int
    {
        return (int) $this->getData(self::ORDER_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setOrderId(int $orderId)
    {
        $this->setData(self::ORDER_ID, $orderId);
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
