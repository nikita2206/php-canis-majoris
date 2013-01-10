<?php

namespace CanisM\Operation\Expr;

use CanisM\Executor\Executor,
    CanisM\Zval;

class Concat extends BinOperation
{

    public function execute(Executor $executor)
    {
        $left = $executor->castString($this->left->execute($executor));
        $right = $executor->castString($this->right->execute($executor));

        return new Zval\Zval(new Zval\StringValue($left . $right));
    }

}
