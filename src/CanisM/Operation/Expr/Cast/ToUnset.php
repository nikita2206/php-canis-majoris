<?php

namespace CanisM\Operation\Expr\Cast;

use CanisM\Executor\Executor,
    CanisM\Zval;

class ToArray extends Cast
{

    public function execute(Executor $executor)
    {
        return new Zval\Zval(new Zval\NullValue());
    }

}
