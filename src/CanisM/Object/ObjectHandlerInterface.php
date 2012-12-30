<?php

namespace CanisM\Object;

use CanisM\HashTable\HashTable,
    CanisM\Zval\Zval;

interface ObjectHandlerInterface
{

    /**
     * @param HashTable|Property[] $defaultProperties
     * @return HashTable|Zval[]
     */
    public function initPropertiesTable(HashTable $defaultProperties);

    public function execConstructor(ClassEntry $classEntry);

}
