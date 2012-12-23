<?php

namespace CanisM;

class HashTableException extends \Exception
{

    public static function elementNotFound($key)
    {
        return new self(sprintf("Element with key \"%s\" was not found.", $key));
    }

}
