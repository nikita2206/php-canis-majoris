<?php

namespace CanisM\Zval;

class StringValue extends Value
{

    /**
     * @var string
     */
    private $value;

    /**
     * @var int
     */
    private $length;


    /**
     * @param string $value
     */
    public function __construct($value = "")
    {
        $this->value = (string)$value;
        $this->length = strlen($this->value);
    }

    /**
     * @param string $value
     * @return StringValue
     */
    public function setValue($value = "")
    {
        $this->value = (string)$value;
        $this->length = strlen($this->value);

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

}
