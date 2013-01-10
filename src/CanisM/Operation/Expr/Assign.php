<?php

namespace CanisM\Operation\Expr;

use CanisM\Operation\Operation,
    CanisM\Executor\Executor;

class Assign extends Operation
{

    /**
     * @var Operation
     */
    protected $variable;

    /**
     * @var Operation
     */
    protected $expression;


    public function __construct(\PHPParser_Node_Expr_Assign $node)
    {
        if ($node->var instanceof \PHPParser_Node_Expr_ArrayDimFetch) {
            $this->variable = new ArrayConstructDimAssign($node->var);
        } elseif ($node->var instanceof \PHPParser_Node_Expr_Variable) {
            $this->variable = new AssignVariable($node->var);
        }

        $this->expression = Operation::factory($node->expr);
    }

    public function execute(Executor $executor)
    {
        $varName = $this->variable->execute($executor);
        $expression = $this->expression->execute($executor);

        $executor->getCurrentContext()->putSymbol($varName, $expression);
    }

}
