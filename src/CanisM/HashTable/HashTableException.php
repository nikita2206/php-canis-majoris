<?php

namespace CanisM\HashTable;

use CanisM\CanisMajorisException;

class HashTableException extends CanisMajorisException
{

    public static function elementNotFound($key)
    {
        return new self(sprintf("Element with key \"%s\" was not found.", $key));
    }

}
