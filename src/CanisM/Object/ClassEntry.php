<?php

namespace CanisM\Object;

use CanisM\HashTable\HashTable,
    CanisM\Func\FuncEntryInterface;

class ClassEntry
{

    /**
     * @var HashTable|Property[]
     */
    private $properties;

    /**
     * @var HashTable<FuncEntryInterface>|FuncEntryInterface[]
     */
    private $methods;

    /**
     * @var ObjectHandlerInterface
     */
    private $objectHandler;

    /**
     * @var string
     */
    private $name;


    /**
     * @param ObjectHandlerInterface $objectHandler
     * @param HashTable              $properties
     * @param HashTable              $methods
     */
    public function __construct(ObjectHandlerInterface $objectHandler, HashTable $properties = null, HashTable $methods = null)
    {
        $this->objectHandler = $objectHandler;
        $this->properties = $properties ?: new HashTable();
        $this->methods = $methods ?: new HashTable();
    }

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

    /**
     * @return HashTable|FuncEntryInterface[]
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @param string $methodName
     * @return bool
     */
    public function hasPublicMethod($methodName)
    {
        if ($this->methods->exists($methodName)) {
            return $this->methods->get($methodName)->isPublic();
        }

        return false;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

}
