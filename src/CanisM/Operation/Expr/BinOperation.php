<?php

namespace CanisM\Operation\Expr;

use CanisM\Operation\Operation;

abstract class BinOperation extends Operation
{

    /**
     * @var Operation
     */
    protected $left;

    /**
     * @var Operation
     */
    protected $right;

    /**
     * @param \PHPParser_Node_Expr_Minus|\PHPParser_Node_Expr_Plus|\PHPParser_Node_Expr_Concat|\PHPParser_Node_Expr_BitwiseAnd|\PHPParser_Node_Expr_BitwiseOr|\PHPParser_Node_Expr_BitwiseXor|\PHPParser_Node_Expr_BooleanAnd|\PHPParser_Node_Expr_BooleanOr|\PHPParser_Node_Expr_Div|\PHPParser_Node_Expr_Mul|\PHPParser_Node_Expr_Equal|\PHPParser_Node_Expr_Greater|\PHPParser_Node_Expr_GreaterOrEqual|\PHPParser_Node_Expr_Identical|\PHPParser_Node_Expr_ShiftLeft|\PHPParser_Node_Expr_ShiftRight|\PHPParser_Node_Expr_LogicalAnd|\PHPParser_Node_Expr_LogicalOr|\PHPParser_Node_Expr_LogicalXor|\PHPParser_Node_Expr_Mod|\PHPParser_Node_Expr_SmallerOrEqual|\PHPParser_Node_Expr_Smaller|\PHPParser_Node_Expr_NotIdentical|\PHPParser_Node_Expr_NotEqual $node
     */
    public function __construct(\PHPParser_Node_Expr $node)
    {
        parent::__construct($node);

        $this->left = Operation::factory($node->left);
        $this->right = Operation::factory($node->right);
    }

}
