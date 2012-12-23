<?php

namespace CanisM;

class ArrayHt implements \ArrayAccess, \IteratorAggregate
{

    /**
     * @var HashTable
     */
    private $decoratedHt;

    public function __construct()
    {
        $this->decoratedHt = new HashTable();
    }

    public function getIterator()
    {
        return $this->decoratedHt;
    }

    public function offsetExists($offset)
    {
        return $this->decoratedHt->exists($offset);
    }

    public function offsetGet($offset)
    {
        return $this->decoratedHt->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $this->decoratedHt->append($value);
        } else {
            $this->decoratedHt->store($offset, $value);
        }
    }

    public function offsetUnset($offset)
    {
        $this->decoratedHt->remove($offset);
    }

}
