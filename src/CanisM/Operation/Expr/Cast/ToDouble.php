<?php

namespace CanisM\Operation\Expr\Cast;

use CanisM\Executor\Executor,
    CanisM\Zval;

class ToDouble extends Cast
{

    public function execute(Executor $executor)
    {
        return new Zval\Zval(new Zval\DoubleValue($executor->castDouble($this->expr->execute($executor))));
    }

}
