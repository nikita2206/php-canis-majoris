<?php

namespace CanisM\Object;

use CanisM\HashTable\HashTable,
    CanisM\Zval\ObjectValue;

class DefaultObjectHandler implements ObjectHandlerInterface
{

    /**
     * {@inheritDoc}
     */
    public function initPropertiesTable(HashTable $defaultProperties)
    {
        $properties = new HashTable();
        foreach ($defaultProperties as $name => $property) {
            $properties->store($name, $property);
        }

        return $properties;
    }

    public function constructObject(ObjectValue $object, \SplFixedArray $arguments)
    {
        $object->executeConstructor();
    }

}
