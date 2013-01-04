<?php

namespace CanisM\Operation\Expr;

use CanisM\Executor\Executor,
    CanisM\Symtable\SymbolNotFoundException,
    CanisM\Zval\Zval;

class FetchVariable extends Variable
{

    public function execute(Executor $executor)
    {
        $varName = $this->getVarName($executor);

        try {
            $container = $executor->getCurrentContext()->getSymbol($varName);
        } catch (SymbolNotFoundException $e) {
            $executor->raiseError(Executor::ERROR_NOTICE, sprintf("Variable \"%s\" not found.", $varName));
            return new Zval();
        }

        return $container;
    }

}
