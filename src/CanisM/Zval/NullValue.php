<?php

namespace CanisM\Zval;

class NullValue extends Value
{

    public function getValue()
    {

    }

    public function setValue()
    {
        throw new \RuntimeException("You can't set value on null.");
    }

}
