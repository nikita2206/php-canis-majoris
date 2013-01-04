<?php

namespace CanisM\Symtable;

use CanisM\HashTable\HashTable,
    CanisM\Zval\Zval;

class Symtable
{

    /**
     * @var HashTable|Zval[]
     */
    private $table;

    /**
     * @var HashTable|null
     */
    private $unreassignable;

    /**
     * @var Symtable
     */
    private $parentTable;


    /**
     * @param \CanisM\HashTable\HashTable $initTable
     * @param Symtable $parentTable
     */
    public function __construct(HashTable $initTable = null, Symtable $parentTable = null)
    {
        $this->table = $initTable ?: new HashTable();
        $this->parentTable = $parentTable;
    }

    public function hasParent()
    {
        return $this->parentTable !== null;
    }

    /**
     * @param string $name
     * @return Zval
     * @throws SymbolNotFoundException
     */
    public function getSymbol($name)
    {
        $name = (string)$name;

        if ($this->table->exists($name)) {
            return $this->table->get($name);
        } elseif ($this->parentTable !== null) {
            return $this->parentTable->getSymbol($name);
        }

        throw new SymbolNotFoundException();
    }

    /**
     * @param string $name
     * @param Zval   $value
     * @param bool   $unreassignable
     * @throws \RuntimeException
     */
    public function putSymbol($name, Zval $value, $unreassignable = false)
    {
        $name = (string)$name;

        if ($this->unreassignable !== null && $this->unreassignable->exists($name)) {
            throw new \RuntimeException("Cannot reassign \$$name");
        }

        $this->table->store($name, $value);

        if ($unreassignable === true) {
            $this->addUnreassignableSymbolName($name);
        }
    }

    /**
     * @param string $name
     */
    public function addUnreassignableSymbolName($name)
    {
        if ($this->unreassignable === null) {
            $this->unreassignable = new HashTable();
        }

        $this->unreassignable->store($name, null);
    }

}
