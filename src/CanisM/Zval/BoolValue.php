<?php

namespace CanisM\Zval;

class BoolValue extends Value
{

    private $value;


    /**
     * @param bool $value
     */
    public function __construct($value = false)
    {
        $this->value = (bool)$value;
    }

    /**
     * @param bool $value
     * @return BoolValue
     */
    public function setValue($value = false)
    {
        $this->value = (bool)$value;
        return $this;
    }

    /**
     * @return bool
     */
    public function getValue()
    {
        return $this->value;
    }

}
