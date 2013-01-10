<?php

namespace CanisM\Zval;

class LongValue extends NumericValue
{

    /**
     * @var int
     */
    private $value;


    /**
     * @param int $value
     */
    public function __construct($value = 0)
    {
        $this->value = (int)$value;
    }

    /**
     * @param int $value
     * @return LongValue
     */
    public function setValue($value = 0)
    {
        $this->value = (int)$value;
        return $this;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

}
