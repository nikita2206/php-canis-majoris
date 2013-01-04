<?php

namespace CanisM\Executor;

use CanisM\Symtable\Symtable,
    CanisM\Func\FuncEntryInterface,
    CanisM\Object\ClassEntry,
    CanisM\Operation\Operation,
    CanisM\Zval;

class Executor
{

    const ERROR_FATAL   = E_ERROR,
          ERROR_WARNING = E_WARNING,
          ERROR_PARSE   = E_PARSE,
          ERROR_NOTICE  = E_NOTICE,

          ERROR_USER_FATAL   = E_USER_ERROR,
          ERROR_USER_WARNING = E_USER_WARNING,
          ERROR_USER_NOTICE  = E_USER_WARNING,

          ERROR_ALL = E_ALL;

    private $errors = array(
        self::ERROR_FATAL        => "Fatal",
        self::ERROR_WARNING      => "Warning",
        self::ERROR_PARSE        => "Parse",
        self::ERROR_NOTICE       => "Notice",
        self::ERROR_USER_FATAL   => "User Fatal",
        self::ERROR_USER_WARNING => "User Warning",
        self::ERROR_USER_NOTICE  => "User Notice"
    );


    /**
     * @var \SplStack|Symtable[]
     */
    private $symtableStack;

    /**
     * @var Symtable
     */
    private $superGlobalSymtable;

    private $inputStream;

    private $outputStream;


    public function __construct($inputStream, $outputStream)
    {
        $this->inputStream = $inputStream;
        $this->outputStream = $outputStream;
    }


    public function execute($code)
    {
        $parser = new \PHPParser_Parser(new \PHPParser_Lexer());

        $ast = array();

        try {
            $ast = $parser->parse($code);
        } catch (\PHPParser_Error $e) {
            $this->raiseError(self::ERROR_PARSE, $e->getMessage());
        }

        /** @var $operations Operation[] */
        $operations = new \SplQueue();
        foreach ($ast as $node) {
            $op = Operation::factory($node);

            if ($op instanceof Declaration) {
                $op->execute($this);
            } else {
                $operations->enqueue($op);
            }
        }

        foreach ($operations as $op) {
            $op->execute($this);
        }
    }

    public function executeFile($filename)
    {

    }

    public function invokeFunction(FuncEntryInterface $function, \SplFixedArray $args = null, Symtable $context = null)
    {

    }

    public function invokeMethod(FuncEntryInterface $function, ClassEntry $classEntry, \SplFixedArray $args = null, Symtable $context = null)
    {

    }

    /**
     * @param Zval\Zval $var
     * @return string
     */
    public function castString(Zval\Zval $var)
    {
        $value = $var->getValue();

        if ($value instanceof Zval\ArrayValue) {
            $this->raiseError(self::ERROR_NOTICE, "Array to string conversion");
            return "Array";
        } elseif ($value instanceof Zval\ObjectValue) {
            if ($value->getClassEntry()->hasPublicMethod("__toString")) {
                $stringValue = $value->executeMethod("__toString", $this);
                if ($stringValue->getValue() instanceof Zval\StringValue) {
                    return $stringValue->getValue()->getValue();
                } else {
                    $this->raiseError(self::ERROR_FATAL, "Method " . $value->getClassEntry()->getName() . "::__toString() must return a string value");
                }
            } else {
                $this->raiseError(self::ERROR_FATAL, "Object of class " . $value->getClassEntry()->getName() . " could not be converted to string");
            }
        } else {
            return (string)$value->getValue();
        }

        return "";
    }

    /**
     * @return Symtable
     */
    public function getCurrentContext()
    {
        return $this->symtableStack->top();
    }

    public function raiseError($level, $message, array $context = null)
    {
        fwrite($this->outputStream, sprintf(
            "%s error: %s", $this->errors[$level], strtr($message, $context ?: array())
        ));

        if ($level & (self::ERROR_FATAL | self::ERROR_PARSE)) {
            $this->stopExecution();
        }
    }

    public function stopExecution()
    {
        throw new \RuntimeException("Process was interrupted.");
    }

}
