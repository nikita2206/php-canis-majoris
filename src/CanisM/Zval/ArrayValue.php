<?php

namespace CanisM\Zval;

use CanisM\HashTable\HashTable;

class ArrayValue extends Value
{

    private $value;

    public function __construct(HashTable $ht = null)
    {
        $this->value = $ht ?: new HashTable();
    }

    public function setValue()
    {
        throw new \RuntimeException("You can't set new HashTable to an ArrayValue.");
    }

    /**
     * @return HashTable
     */
    public function getValue()
    {
        return $this->value;
    }

}
