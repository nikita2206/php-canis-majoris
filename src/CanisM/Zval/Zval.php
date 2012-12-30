<?php

namespace CanisM\Zval;

class Zval
{

    /**
     * @var Value
     */
    private $value;

    /**
     * @var bool
     */
    private $isReference;

    /**
     * @var int
     */
    private $referencesCount;

    /**
     * @param Value $value
     * @param bool  $isReference
     */
    public function __construct(Value $value = null, $isReference = false)
    {
        $this->value = $value ?: new NullValue();
        $this->isReference = $isReference;
    }

    /**
     * @return Zval
     */
    public function incrementReferencesCount()
    {
        ++$this->referencesCount;
        return $this;
    }

    /**
     * @return Zval
     */
    public function decrementReferencesCount()
    {
        --$this->referencesCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getReferencesCount()
    {
        return $this->referencesCount;
    }

    /**
     * @param Value $value
     * @return Zval
     */
    public function setValue(Value $value = null)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return Value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param bool $isReference
     * @return Zval
     */
    public function setIsReference($isReference)
    {
        $this->isReference = $isReference;
        return $this;
    }

    /**
     * @return bool
     */
    public function isReference()
    {
        return $this->isReference;
    }

}
