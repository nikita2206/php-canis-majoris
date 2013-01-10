<?php

namespace CanisM\Operation\Expr;

use CanisM\Executor\Executor,
    CanisM\Zval;

class Equal extends BinOperation
{

    public function execute(Executor $executor)
    {
        $left = $this->left->execute($executor);
        $right = $this->right->execute($executor);

        if ($left->getValue() instanceof Zval\ObjectValue && $right->getValue() instanceof Zval\ObjectValue) {
            return $left->getValue() === $right->getValue();
        }

        return new Zval\Zval(new Zval\BoolValue($left->getValue()->getValue() == $right->getValue()->getValue()));
    }

}
