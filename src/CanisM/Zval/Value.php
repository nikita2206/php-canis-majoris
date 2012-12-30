<?php

namespace CanisM\Zval;

abstract class Value
{

    const TYPE_LONG     = 0,
          TYPE_BOOL     = 1,
          TYPE_STRING   = 2,
          TYPE_DOUBLE   = 3,
          TYPE_ARRAY    = 4,
          TYPE_OBJECT   = 5,
          TYPE_RESOURCE = 6,
          TYPE_NULL     = 7;

    abstract function getValue();

    abstract function setValue();

}
