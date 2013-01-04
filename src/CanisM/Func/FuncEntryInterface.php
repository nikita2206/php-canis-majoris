<?php

namespace CanisM\Func;

use CanisM\HashTable\HashTable,
    CanisM\Executor\Executor;

interface FuncEntryInterface
{

    public function execute(Executor $executor, HashTable $arguments, HashTable $context);

}
