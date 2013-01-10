<?php

namespace CanisM\Operation\Expr;

use CanisM\Executor\Executor,
    CanisM\Zval;

class BitwiseOr extends BinaryBitwise
{

    public function execute(Executor $executor)
    {
        $left = $this->left->execute($executor);
        $right = $this->right->execute($executor);

        $left = $this->getSuitableValue($executor, $left);
        $right = $this->getSuitableValue($executor, $right);

        $value = $left | $right;

        return new Zval\Zval(is_int($value) ? new Zval\LongValue($value) : new Zval\StringValue($value));
    }

}
