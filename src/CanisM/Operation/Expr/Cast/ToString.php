<?php

namespace CanisM\Operation\Expr\Cast;

use CanisM\Executor\Executor,
    CanisM\Zval;

class ToString extends Cast
{

    public function execute(Executor $executor)
    {
        return new Zval\Zval(new Zval\StringValue($executor->castString($this->expr->execute($executor))));
    }

}
