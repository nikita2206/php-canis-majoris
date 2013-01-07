<?php

namespace CanisM\Operation\Expr;

use CanisM\Operation\Operation,
    CanisM\Executor\Executor,
    CanisM\Zval;

class Concat extends Operation
{

    /**
     * @var \CanisM\Operation\Operation
     */
    private $left;

    /**
     * @var \CanisM\Operation\Operation
     */
    private $right;


    public function __construct(\PHPParser_Node_Expr_Concat $node)
    {
        parent::__construct($node);

        $this->left = Operation::factory($node->left);
        $this->right = Operation::factory($node->right);
    }

    public function execute(Executor $executor)
    {
        $left = $executor->castString($this->left->execute($executor));
        $right = $executor->castString($this->right->execute($executor));

        return new Zval\Zval(new Zval\StringValue($left . $right));
    }

}
