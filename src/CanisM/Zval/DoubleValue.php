<?php

namespace CanisM\Zval;

class DoubleValue extends Value
{

    /**
     * @var float
     */
    private $value;

    /**
     * @param float $value
     */
    public function __construct($value = 0.0)
    {
        $this->value = (float)$value;
    }

    /**
     * @param float $value
     * @return DoubleValue
     */
    public function setValue($value = 0.0)
    {
        $this->value = (float)$value;
        return $this;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

}
