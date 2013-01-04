<?php

namespace CanisM\Operation\Expr;

use CanisM\Operation\Operation,
    CanisM\Executor\Executor,
    CanisM\Zval\StringValue;

abstract class Variable extends Operation
{

    /**
     * @var Operation|string
     */
    protected $name;

    public function __construct(\PHPParser_Node_Expr_Variable $node)
    {
        parent::__construct($node);

        $this->name = is_object($node->name) ? Operation::factory($node->name) : $node->name;
    }

    /**
     * @param \CanisM\Executor\Executor $executor
     * @return string
     */
    protected function getVarName(Executor $executor)
    {
        $name = $this->name;

        if (is_scalar($name)) {
            return (string)$name;
        } else {
            $name = $name->execute($executor);

            return $executor->castString($name);
        }
    }

}
