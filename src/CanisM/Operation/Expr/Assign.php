<?php

namespace CanisM\Operation\Expr;

use CanisM\Operation\Operation,
    CanisM\Executor\Executor;

class Assign extends Operation
{

    /**
     * @var \CanisM\Operation\Operation
     */
    protected $variable;

    /**
     * @var \CanisM\Operation\Operation
     */
    protected $expression;


    public function __construct(\PHPParser_Node_Expr_Assign $node)
    {
        $this->variable = new AssignVariable($node->var);
        $this->expression = Operation::factory($node->expr);
    }

    public function execute(Executor $executor)
    {
        $variable = $this->variable->execute($executor);
        $expression = $this->expression->execute($executor);

        $variable->setValue($expression->getValue());
    }

}
