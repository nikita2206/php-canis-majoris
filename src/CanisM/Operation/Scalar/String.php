<?php

namespace CanisM\Operation\Scalar;

use CanisM\Executor\Executor,
    CanisM\Zval;

class String extends Scalar
{

    public function execute(Executor $executor)
    {
        return new Zval\Zval(new Zval\StringValue($this->value));
    }

}
