<?php

namespace CanisM\Operation;

use CanisM\Executor\Executor,
    CanisM\Zval\Zval;

abstract class Operation
{

    private static $replaceMap = array(
        "Echo" => "Cout",
        "Expr_Variable" => "Expr_FetchVariable",
        "Expr_Array" => "Expr_ArrayConstruct",
        "Cast_Array" => "Cast_ToArray",
        "Cast_Bool"  => "Cast_ToBool",
        "Cast_Int"   => "Cast_ToLong",
        "Cast_Double" => "Cast_ToDouble",
        "Cast_Object" => "Cast_ToObject",
        "Cast_String" => "Cast_ToString",
        "Cast_Unset"  => "Cast_ToUnset"
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
