<?php
/**
 * Donin
 *
 * @category Donin
 * @package Donin_AdministrationFee
 */ 
declare(strict_types=1);

namespace Donin\AdministrationFee\Model\Config;

use Donin\AdministrationFee\Model\Checkout\Summary\Calculation\CalculationInterface;

class TypePool
{
    /**
     * @var array
     */
    private array $types;

    /**
     * @param array $types
     */
    public function __construct(array $types)
    {
        $this->types = $types;
    }

    /**
     * @param string $type
     * @return CalculationInterface
     */
    public function getAmountCalculator(string $type): CalculationInterface
    {
        if (!isset($this->types[$type])) {
            throw new \LogicException($type . ' does not have appropriate configuration. Unknown configuration provided');
        }

        if (!$this->types[$type] instanceof CalculationInterface) {
            throw new \LogicException(get_class($this->types[$type]) . ' must implement ' . CalculationInterface::class);
        }

        return $this->types[$type];
    }
}
