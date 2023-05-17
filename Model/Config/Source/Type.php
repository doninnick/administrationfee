<?php
/**
 * Donin
 *
 * @category Donin
 * @package Donin_AdministrationFee
 */ 
declare(strict_types=1);

namespace Donin\AdministrationFee\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Type implements ArrayInterface
{
    const TYPE_PERCENT = 'percent';
    const TYPE_FIXED = 'fixed';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            self::TYPE_FIXED => __('Fixed'),
            self::TYPE_PERCENT => __('Percent')
        ];
    }
}
