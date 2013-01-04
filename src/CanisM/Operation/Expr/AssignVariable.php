<?php

namespace CanisM\Operation\Expr;

use CanisM\Executor\Executor,
    CanisM\Zval\Zval;

class AssignVariable extends Variable
{

    public function execute(Executor $executor)
    {
        $varName = $this->getVarName($executor);

        $executor->getCurrentContext()->putSymbol($varName, $container = new Zval());

        return $container;
    }

}
