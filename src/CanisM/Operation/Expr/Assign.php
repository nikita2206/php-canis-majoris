<?php

namespace CanisM\Operation\Expr;

use CanisM\Operation\Operation,
    CanisM\Executor\Executor;

class Assign extends Operation
{

    protected $expr;

    protected $var;


    public function __construct(\PHPParser_Node_Expr_Assign $node)
    {
        $node->expr = $node->expr;
    }

    public function execute(Executor $executor)
    {

    }

}
