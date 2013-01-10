<?php

namespace CanisM\Operation\Expr\Cast;

use CanisM\Operation\Operation;

abstract class Cast extends Operation
{

    /**
     * @var Operation
     */
    protected $expr;

    public function __construct(\PHPParser_Node_Expr_Cast $node)
    {
        parent::__construct($node);

        $this->expr = Operation::factory($node->expr);
    }

}
