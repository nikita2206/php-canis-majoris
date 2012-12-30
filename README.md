Hash Table
==========

This is how php's arrays are internally implemented.
It was written to show how arrays work in php.

How to use
----------

    <?php

    use CanisM\HashTable;

    // $ar = array();
    $ht = new HashTable();

    // $ar[] = "foo";
    $ht->append("foo");

    // $ar["foo"] = "bar";
    $ht->store("foo", "bar");

    // $ar[0];
    $ht->get(0);

    // isset($ar["foo"]);
    $ht->exists("foo");

    // unset($ar["foo"]);
    $ht->remove("foo");

    foreach ($ht as $k => $v) {
        echo sprintf("%s => %s", $k, $v);
    }


There's also ArrayHt class that can fully emulate array, so you can do:

    <?php

    use CanisM\ArrayHt;

    $ar = new ArrayHt;

    $ar[] = "foo";
    $ar["foo"] = "bar";
    $ar[0];
    isset($ar["foo"]);
    unset($ar["foo"]);


How it works
------------

This is an implementation of Hash Table (http://en.wikipedia.org/wiki/Hash_table) with separate chaining as collision solution.
But this code was mostly written from the C implementation used in PHP core.