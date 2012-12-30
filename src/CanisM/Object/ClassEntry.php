<?php

namespace CanisM\Object;

use CanisM\HashTable\HashTable;

class ClassEntry
{

    /**
     * @var HashTable|Property[]
     */
    private $properties;

    /**
     * @var ObjectHandlerInterface
     */
    private $objectHandler;

    /**
     * @return HashTable|Property[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @return ObjectHandlerInterface
     */
    public function getObjectHandler()
    {
        return $this->objectHandler;
    }

}
