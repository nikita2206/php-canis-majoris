#!/usr/bin/php
<?php

require __DIR__ . "/vendor/autoload.php";

use CanisM\HashTable\HashTable,
    CanisM\Executor as Exe;

$executor = new Exe\Executor(STDIN, STDOUT, new Exe\ErrorThrower(), new HashTable());

if ($argc === 0) {
    echo "no arguments were passed";
} elseif ($argv[1] === "-r") {
    $executor->execute("<" . "?php " . $argv[2]);
} elseif (is_file($argv[1])) {
    $executor->executeFile($argv[1]);
} else {
    print_r($argv);
}
