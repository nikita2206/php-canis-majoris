<?php

namespace CanisM\Operation\Expr;

use CanisM\Operation\Operation,
    CanisM\Executor\Executor;

class ArrayConstructDimFetch extends Operation
{

    /**
     * @var Operation
     */
    private $dim;

    /**
     * @var Operation
     */
    private $var;


    public function __construct(\PHPParser_Node_Expr_ArrayDimFetch $node)
    {
        // here's dim guaranteed to not be null

        $this->dim = Operation::factory($node->dim);
        $this->var = Operation::factory($node->var);
    }

    public function execute(Executor $executor)
    {
        $var = $this->var->execute($executor);
        $dim = $this->dim->execute($executor);


    }

}
