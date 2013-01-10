<?php

namespace CanisM\Executor;

use CanisM\Symtable\Symtable,
    CanisM\HashTable\HashTable,
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

    public $errors = array(
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

    /**
     * @var resource
     */
    private $inputStream;

    /**
     * @var resource
     */
    private $outputStream;

    /**
     * @var ErrorHandler
     */
    private $errorHandler;


    public function __construct($inputStream, $outputStream, ErrorHandler $errorHandler, HashTable $superGlobalScope, HashTable $globalScope = null)
    {
        $this->inputStream = $inputStream;
        $this->outputStream = $outputStream;
        $this->errorHandler = $errorHandler;

        $this->superGlobalSymtable = $superGlobal = new Symtable($superGlobalScope);

        $global = new Symtable($globalScope, $superGlobal);

        $this->symtableStack = new \SplStack();
        $this->symtableStack->push($global);
    }

    /**
     * @param string $code Code to execute
     */
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
        $operations = array();
        foreach ($ast as $node) {
            $op = Operation::factory($node);

            /*if ($op instanceof Declaration) {
                $op->execute($this);
            } else {*/
                $operations[] = $op;
            /*}*/
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
     * @param Zval\Zval $var
     * @return int
     */
    public function castInteger(Zval\Zval $var)
    {
        $value = $var->getValue();

        if ($value instanceof Zval\ArrayValue) {
            return (int)($value->getValue()->count() > 0);
        } elseif ($value instanceof Zval\DoubleValue || $value instanceof Zval\StringValue) {
            return (int)$value->getValue();
        } elseif ($value instanceof Zval\NullValue) {
            return 0;
        } elseif ($value instanceof Zval\ObjectValue) {
            $this->raiseError(self::ERROR_NOTICE, "Object of class " . $value->getClassEntry()->getName() . " could not be converted to int");
            return 0;
        } elseif ($value instanceof Zval\BoolValue) {
            return $value->getValue() === true ? 1 : 0;
        }

        return $value->getValue();
    }

    /**
     * @param Zval\Zval $var
     * @return float
     */
    public function castDouble(Zval\Zval $var)
    {
        $value = $var->getValue();

        if ($value instanceof Zval\ArrayValue) {
            return (float)($value->getValue()->count() > 0);
        } elseif ($value instanceof Zval\LongValue || $value instanceof Zval\StringValue) {
            return (float)$value->getValue();
        } elseif ($value instanceof Zval\NullValue) {
            return .0;
        } elseif ($value instanceof Zval\ObjectValue) {
            $this->raiseError(self::ERROR_NOTICE, "Object of class " . $value->getClassEntry()->getName() . " could not be converted to double");
            return 0;
        } elseif ($value instanceof Zval\BoolValue) {
            return $value->getValue() === true ? 1.0 : .0;
        }

        return $value->getValue();
    }

    /**
     * @param Zval\Zval $var
     * @return float|int
     */
    public function castNumeric(Zval\Zval $var)
    {
        if ($var->getValue() instanceof Zval\NumericValue) {
            return $var->getValue()->getValue();
        }

        $double = $this->castDouble($var);
        $int    = $this->castInteger($var);

        return $double == $int ? $int : $double;
    }

    /**
     * @param Zval\Zval $var
     * @return bool
     */
    public function castBoolean(Zval\Zval $var)
    {
        $value = $var->getValue();

        if ($value instanceof Zval\ArrayValue) {
            return $value->getValue()->count() > 0;
        } elseif ($value instanceof Zval\LongValue) {
            return $value->getValue() !== 0;
        } elseif ($value instanceof Zval\NullValue) {
            return false;
        } elseif ($value instanceof Zval\StringValue) {
            return (bool)$value->getValue();
        } elseif ($value instanceof Zval\DoubleValue) {
            return $value->getValue() !== .0;
        } elseif ($value instanceof Zval\ObjectValue) {
            return true;
        }

        return $value->getValue();
    }

    /**
     * @param Zval\Zval $left
     * @param Zval\Zval $right
     * @param bool $strict
     * @return bool
     */
    public function isZvalsIdentical(Zval\Zval $left, Zval\Zval $right, $strict = false)
    {
        return $this->compareZvals($left, $right, $strict) === 0;
    }

    /**
     * @param Zval\Zval $left
     * @param Zval\Zval $right
     * @param bool $strict
     * @param int $nesting
     * @return int|null
     */
    public function compareZvals(Zval\Zval $left, Zval\Zval $right, $strict = false, $nesting = 0)
    {

        if ($nesting > 100) {
            $this->raiseError(self::ERROR_FATAL, "Nesting level too deep - recursive dependency?");
        }

        $leftValue = $left->getValue();
        $rightValue = $right->getValue();

        if ($leftValue === $rightValue) {
            return 0;
        }

        if ($strict === true && get_class($leftValue) !== get_class($rightValue)) {
            return -1;
        }

        if ($leftValue instanceof Zval\NumericValue || $rightValue instanceof Zval\NumericValue) {
            $leftValue = $this->castNumeric($left);
            $rightValue = $this->castNumeric($right);

            return $leftValue == $rightValue ? 0 : ($leftValue > $rightValue ? 1 : -1);
        }

        if (
            $leftValue instanceof Zval\NullValue || $rightValue instanceof Zval\NullValue
            ||
            $leftValue instanceof Zval\BoolValue || $rightValue instanceof Zval\BoolValue
        ) {
            $leftValue = $this->castBoolean($left);
            $rightValue = $this->castBoolean($right);

            return $leftValue === $rightValue ? 0 : ($leftValue === true ? 1 : -1);
        }

        if ($leftValue instanceof Zval\StringValue || $rightValue instanceof Zval\StringValue) {
            $leftValue = $this->castString($left);
            $rightValue = $this->castString($right);

            return $leftValue === $rightValue ? 0 : ($leftValue > $rightValue ? 1 : -1);
        }

        if ($leftValue instanceof Zval\ArrayValue && $rightValue instanceof Zval\ArrayValue) {
            return $this->compareHashTables($leftValue->getValue(), $rightValue->getValue(), $strict, $nesting);
        }

        if ($leftValue instanceof Zval\ObjectValue && $rightValue instanceof Zval\ObjectValue) {
            if ($strict) {
                return (int)!($leftValue === $rightValue);
            } else {
                if ($leftValue->getClassEntry() !== $rightValue->getClassEntry()) {
                    return -1;
                }

                return $this->compareHashTables($leftValue->getProperties(), $rightValue->getProperties(), false, $nesting);
            }
        }

        return null;

    }

    public function compareHashTables(HashTable $left, HashTable $right, $strict = false, $nesting = 0)
    {
        if ($left === $right) {
            return true;
        }

        if ($left->count() !== $right->count()) {
            return $left->count() - $right->count();
        }

        $left->rewind();
        $right->rewind();

        while ($left->valid() && $right->valid()) {
            if ($left->key() !== $right->key()) {
                $left->rewind();
                $right->rewind();
                return null;
            }

            if (0 !== $result = $this->compareZvals($left->current(), $right->current(), $strict, $nesting + 1)) {
                $left->rewind();
                $right->rewind();
                return $result;
            }

            $left->next();
            $right->next();
        }

        $left->rewind();
        $right->rewind();

        return 0;
    }

    /**
     * @return Symtable
     */
    public function getCurrentContext()
    {
        return $this->symtableStack->top();
    }

    /**
     * @param Zval\Zval $var
     */
    public function cout(Zval\Zval $var)
    {
        fwrite($this->outputStream, $this->castString($var));
    }

    /**
     * @param string $string
     */
    public function sendOutput($string)
    {
        fwrite($this->outputStream, $string);
    }

    public function raiseError($level, $message, array $context = null)
    {
        fwrite($this->outputStream, sprintf(
            "%s error: %s\n", $this->errors[$level], strtr($message, $context ?: array())
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
