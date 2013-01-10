<?php

namespace CanisM\Operation\Expr;

use CanisM\Operation\Operation,
    CanisM\Executor\Executor,
    CanisM\Zval\Zval;

class ArrayConstructItem extends Operation
{

    /**
     * @var bool
     */
    private $byRef = false;

    /**
     * @var Operation|null
     */
    private $key;

    /**
     * @var Operation
     */
    private $value;

    /**
     * @var Zval
     */
    private $compiledKey;

    /**
     * @var Zval
     */
    private $compiledValue;


    public function __construct(\PHPParser_Node_Expr_ArrayItem $node)
    {
        parent::__construct($node);

        $key = $node->key;

        $this->byRef = $node->byRef;
        $this->key   = $key === null ? null : Operation::factory($key);
        $this->value = Operation::factory($node->value);
    }

    public function execute(Executor $executor)
    {
        $this->compiledKey = $this->key === null ? null : $this->key->execute($executor);
        $this->compiledValue = $this->value->execute($executor);

        return $this->compiledValue;
    }

    /**
     * @return Zval
     */
    public function getCompiledValue()
    {
        return $this->compiledValue;
    }

    /**
     * @return Zval
     */
    public function getCompiledKey()
    {
        return $this->compiledKey;
    }

}
