<?php

namespace CanisM\Object;

use CanisM\Zval\Zval;

class Property
{

    const VISIBILITY_PUBLIC    = 0,
          VISIBILITY_PROTECTED = 1,
          VISIBILITY_PRIVATE   = 2;

    private $visibility;

    /**
     * @var Zval
     */
    private $defaultValue;

    /**
     * @param int  $visibility
     * @param Zval $defaultValue
     */
    public function __construct($visibility = self::VISIBILITY_PUBLIC, Zval $defaultValue = null)
    {
        $this->visibility = $visibility;
        $this->defaultValue = $defaultValue;
    }

    /**
     * @return int
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * @return bool
     */
    public function hasDefaultValue()
    {
        return $this->defaultValue !== null;
    }

    /**
     * @return Zval|null
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

}
