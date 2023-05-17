<?php
/**
 * Donin
 *
 * @category Donin
 * @package Donin_AdministrationFee
 */ 
declare(strict_types=1);

namespace DoninConsulting\AdministrationFee\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use DoninConsulting\AdministrationFee\Api\Data\AdministrationFeeQuoteInterface;

class AdministrationFeeQuote extends AbstractDb
{
    const MAIN_TABLE = 'donin_administration_fee_quote';

    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE, AdministrationFeeQuoteInterface::ENTITY_ID);
    }
}
