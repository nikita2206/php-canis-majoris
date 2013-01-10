<?php

namespace CanisM\Operation\Expr\Cast;

use CanisM\Executor\Executor,
    CanisM\Zval;

class ToBool extends Cast
{

    public function execute(Executor $executor)
    {
        return new Zval\Zval(new Zval\BoolValue($executor->castBoolean($this->expr->execute($executor))));
    }

}
