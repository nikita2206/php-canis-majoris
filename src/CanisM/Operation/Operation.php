<?php

namespace CanisM\Operation;

use CanisM\Executor\Executor,
    CanisM\Zval\Zval;

abstract class Operation
{

    private static $replaceMap = array(
        "Echo" => "Cout",
        "Expr_Variable" => "Expr_FetchVariable",
        "Expr_Array" => "Expr_ArrayConstruct"
    );

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
        $nodeClass = explode("_", strtr(get_class($node), self::$replaceMap));
        if (isset($nodeClass[4])) {
            $className = sprintf("\\CanisM\\Operation\\%s\\%s\\%s", $nodeClass[2], $nodeClass[3], $nodeClass[4]);
        } else {
            $className = sprintf("\\CanisM\\Operation\\%s\\%s", $nodeClass[2], $nodeClass[3]);
        }

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
