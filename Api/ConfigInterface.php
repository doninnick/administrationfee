<?php
/**
 * Donin
 *
 * @category Donin
 * @package Donin_AdministrationFee
 */ 
declare(strict_types=1);

namespace Donin\AdministrationFee\Api;

/**
 * Interface ConfigInterface
 */
interface ConfigInterface
{
    /**
     * Check if Administration Fee extension is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * Get Administration Fee type
     *
     * @return string
     */
    public function getAdminFeeType(): string;

    /**
     * Get Administration Fee Amount
     *
     * @return float
     */
    public function getAdminFeeAmount(): float;

    /**
     * Returns array of the Shipping country codes
     *
     * @return array
     */
    public function getShippingCountries(): array;

    /**
     * Get Administration label
     *
     * @return string
     */
    public function getLabel(): string;
}
