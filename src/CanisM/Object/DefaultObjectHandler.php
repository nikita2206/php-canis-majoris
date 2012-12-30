<?php

namespace CanisM\Object;

use CanisM\HashTable\HashTable;

class DefaultObjectHandler implements ObjectHandlerInterface
{

    /**
     * {@inheritDoc}
     */
    public function initPropertiesTable(HashTable $defaultProperties)
    {
        $properties = new HashTable();
        foreach ($classEntry as $name => $property) {
            $properties->store($name, $property);
        }

        return $properties;
    }

}
