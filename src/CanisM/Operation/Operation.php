<?php

namespace CanisM\Operation;

use CanisM\Executor\Executor,
    CanisM\Zval\Zval;

abstract class Operation
{

    /**
     * @var int
     */
    protected $line;


    /**
     * @param \PHPParser_Node $node
     * @return Operation
     */
    public static function factory(\PHPParser_Node $node)
    {
        $nodeClass = explode("_", get_class($node));
        $className = sprintf("\\CanisM\\Operation\\%s\\%s", $nodeClass[2], $node[3]);

        return new $className($node);
    }

    public function __construct(\PHPParser_Node $node)
    {
        $this->line = $node->getLine();
    }

    public function getLine()
    {
        return $this->line;
    }

    /**
     * @param \CanisM\Executor\Executor $executor
     * @return Zval
     */
    abstract public function execute(Executor $executor);

}
