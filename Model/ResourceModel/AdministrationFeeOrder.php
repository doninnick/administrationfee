<?php
/**
 * Donin
 *
 * @category Donin
 * @package Donin_AdministrationFee
 */ 
declare(strict_types=1);

namespace Donin\AdministrationFee\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Donin\AdministrationFee\Api\Data\AdministrationFeeOrderInterface;

class AdministrationFeeOrder extends AbstractDb
{
    const MAIN_TABLE = 'donin_administration_fee_order';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE, AdministrationFeeOrderInterface::ENTITY_ID);
    }
}
