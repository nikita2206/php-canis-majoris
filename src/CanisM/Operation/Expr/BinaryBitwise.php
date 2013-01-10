<?php

namespace CanisM\Operation\Expr;

use CanisM\Zval,
    CanisM\Executor\Executor;

abstract class BinaryBitwise extends BinOperation
{

    /**
     * @param Executor $executor
     * @param Zval\Zval $var
     * @return int|string
     */
    protected function getSuitableValue(Executor $executor, Zval\Zval $var)
    {
        if (!$var->getValue() instanceof Zval\LongValue && !$var->getValue() instanceof Zval\StringValue) {
            return $executor->castInteger($var);
        } else {
            return $var->getValue()->getValue();
        }
    }

}
