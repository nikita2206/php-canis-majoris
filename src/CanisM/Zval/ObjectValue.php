<?php

namespace CanisM\Zval;

use CanisM\HashTable\HashTable,
    CanisM\Object\ClassEntry,
    CanisM\Executor\Executor;

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

    /**
     * @param $methodName
     * @param Executor $executor
     * @return Zval
     */
    public function executeMethod($methodName, Executor $executor)
    {
        if (!$this->classEntry->getMethods()->exists($methodName)) {
            $executor->raiseError(Executor::ERROR_FATAL, "Call to undefined method {class}::{method}()", array(
                "{class}"  => $this->classEntry->getName(),
                "{method}" => $methodName
            ));
            return;
        }

        /** @var $method \CanisM\Func\FuncEntryInterface */
        $method = $this->classEntry->getMethods()->get($methodName);

    }

    /**
     * @return ClassEntry
     */
    public function getClassEntry()
    {
        return $this->classEntry;
    }

    /**
     * @return HashTable|Zval[]
     */
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
