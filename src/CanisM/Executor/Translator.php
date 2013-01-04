<?php

namespace CanisM\Executor;

use CanisM\Zval\Zval;

class Translator
{

    /**
     * @var Executor
     */
    private $executor;

    /**
     * @param \PHPParser_Node_Stmt $statement
     * @return Zval
     */
    public function executeStatement(\PHPParser_Node_Stmt $statement)
    {

    }

    /**
     * @param \PHPParser_Node_Expr $expression
     * @return Zval
     */
    public function resolveExpression(\PHPParser_Node_Expr $expression)
    {

    }

}
