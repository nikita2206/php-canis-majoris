<?php

namespace CanisM\Operation\Expr;

use CanisM\Executor\Executor,
    CanisM\Zval;

class Mul extends BinOperation
{

    public function execute(Executor $executor)
    {
        $left = $this->left->execute($executor);
        $right = $this->right->execute($executor);

        $result = $left->getValue()->getValue() * $right->getValue()->getValue();

        return new Zval\Zval(is_double($result) ? new Zval\DoubleValue($result) : new Zval\LongValue($result));
    }

}
