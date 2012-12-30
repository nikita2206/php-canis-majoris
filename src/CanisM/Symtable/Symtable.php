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
     * @var Symtable
     */
    private $parentTable;


    public function __construct(HashTable $initTable = null, Symtable $parentTable = null)
    {
        $this->table = $initTable ?: new HashTable();
        $this->parentTable = $parentTable;
    }

    public function hasParent()
    {
        return $this->parentTable !== null;
    }

    public function getSymbol($name)
    {
        if ($this->table->exists($name)) {
            return $this->table->get($name);
        } elseif ($this->parentTable !== null) {
            return $this->parentTable->getSymbol($name);
        }

        throw new SymbolNotFoundException();
    }

}
