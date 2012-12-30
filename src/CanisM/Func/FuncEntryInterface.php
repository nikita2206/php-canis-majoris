<?php

namespace CanisM\Func;

use CanisM\HashTable\HashTable;

interface FuncEntryInterface
{

    public function execute(Executor $executor, HashTable$arguments, HashTable $scope);

}
