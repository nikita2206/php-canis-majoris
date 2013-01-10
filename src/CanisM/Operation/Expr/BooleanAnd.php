<?php

namespace CanisM\Operation\Expr;

use CanisM\Executor\Executor,
    CanisM\Zval;

class BooleanAnd extends BinOperation
{

    public function execute(Executor $executor)
    {
        $left = $this->left->execute($executor);
        $right = $this->right->execute($executor);

        return new Zval\Zval(new Zval\BoolValue($executor->castBoolean($left) && $executor->castBoolean($right)));
    }

}
