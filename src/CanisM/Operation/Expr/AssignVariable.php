<?php

namespace CanisM\Operation\Expr;

use CanisM\Executor\Executor,
    CanisM\Zval\Zval;

class AssignVariable extends Variable
{

    /**
     * @param Executor $executor
     * @return string
     */
    public function execute(Executor $executor)
    {
        $varName = $this->getVarName($executor);

        return $varName;
    }

}
