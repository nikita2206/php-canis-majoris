<?php

namespace CanisM\Operation\Expr;

use CanisM\Executor\Executor,
    CanisM\Zval;

class Identical extends BinOperation
{

    public function execute(Executor $executor)
    {
        $left = $this->left->execute($executor);
        $right = $this->right->execute($executor);

        if (get_class($left->getValue()) !== get_class($right->getValue())) {
            return new Zval\Zval(new Zval\BoolValue(false));
        }

        return new Zval\Zval(new Zval\BoolValue($left->getValue()->getValue() == $right->getValue()->getValue()));
    }

}
