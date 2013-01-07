<?php

namespace CanisM\Operation\Scalar;

use CanisM\Operation\Operation;

abstract class Scalar extends Operation
{

    /**
     * @var scalar
     */
    protected $value;

    public function __construct(\PHPParser_Node_Scalar $node)
    {
        parent::__construct($node);

        if (isset($node->value)) {
            $this->value = $node->value;
        }
    }

}
