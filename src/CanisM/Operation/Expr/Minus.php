<?php

namespace CanisM\Operation\Expr;

use CanisM\Operation\Operation,
    CanisM\Executor\Executor,
    CanisM\Zval;

class Minus extends Operation
{

    /**
     * @var \CanisM\Operation\Operation
     */
    private $left;

    /**
     * @var \CanisM\Operation\Operation
     */
    private $right;


    public function __construct(\PHPParser_Node_Expr_Minus $node)
    {
        parent::__construct($node);

        $this->left = Operation::factory($node->left);
        $this->right = Operation::factory($node->right);
    }

    public function execute(Executor $executor)
    {
        $left = $this->left->execute($executor);
        $right = $this->right->execute($executor);

        $result = $left->getValue()->getValue() - $right->getValue()->getValue();

        return new Zval\Zval($left->getValue() instanceof Zval\DoubleValue || $right->getValue() instanceof Zval\DoubleValue ?
            new Zval\DoubleValue($result) : new Zval\LongValue($result));
    }

}
