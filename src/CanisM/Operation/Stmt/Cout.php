<?php

namespace CanisM\Operation\Stmt;

use CanisM\Operation\Operation,
    CanisM\Executor\Executor;

class Cout extends Operation
{

    /**
     * @var Operation[]
     */
    private $expressions = array();

    public function __construct(\PHPParser_Node_Stmt_Echo $node)
    {
        parent::__construct($node);

        foreach ($node->exprs as $expression) {
            $this->expressions[] = Operation::factory($expression);
        }
    }

    public function execute(Executor $executor)
    {
        foreach ($this->expressions as $expression) {
            $executor->cout($expression->execute($executor));
        }
    }

}
