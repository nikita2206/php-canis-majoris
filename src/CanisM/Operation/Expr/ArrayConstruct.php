<?php

namespace CanisM\Operation\Expr;

use CanisM\Operation\Operation,
    CanisM\Executor\Executor,
    CanisM\Zval,
    CanisM\HashTable\HashTable;

class ArrayConstruct extends Operation
{

    /**
     * @var ArrayConstructItem[]
     */
    private $items = array();

    public function __construct(\PHPParser_Node_Expr_Array $node)
    {
        parent::__construct($node);

        foreach ($node->items as $item) {
            $this->items[] = new ArrayConstructItem($item);
        }
    }

    public function execute(Executor $executor)
    {
        $ht = new HashTable();
        $var = new Zval\Zval(new Zval\ArrayValue($ht));

        foreach ($this->items as $item) {
            $item->execute($executor);

            if ($item->getCompiledKey() === null) {
                $ht->append($item->getCompiledValue());
            } else {
                $ht->store($item->getCompiledKey(), $item->getCompiledValue());
            }
        }

        return $var;
    }

}
