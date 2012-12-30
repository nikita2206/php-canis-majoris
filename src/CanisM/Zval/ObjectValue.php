<?php

namespace CanisM\Zval;

use CanisM\HashTable\HashTable,
    CanisM\Object\ClassEntry;

class ObjectValue extends Value
{

    /**
     * @var ClassEntry
     */
    private $classEntry;

    /**
     * @var HashTable
     */
    private $properties;

    /**
     * Guards needed to protect us from recursion on __get,__set.
     *
     * @var HashTable
     */
    private $guards;


    /**
     * @param ClassEntry $classEntry
     */
    public function __construct(ClassEntry $classEntry)
    {
        $this->classEntry = $classEntry;
        $this->guards = new HashTable();

        $this->properties = $classEntry->getObjectHandler()->initPropertiesTable($classEntry->getProperties());
    }

    public function executeConstructor(\SplFixedArray $arguments)
    {

    }

    public function getProperties()
    {
        return $this->properties;
    }


    public function getValue()
    {
        throw new \RuntimeException("You can't get value of the object.");
    }

    public function setValue() {
        throw new \RuntimeException("You can't set value of the object.");
    }

}
