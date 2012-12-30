<?php

namespace CanisM\Test;

class HashTableTest extends \PHPUnit_Framework_TestCase
{

    public function testStoreAndRetrieveByIdxWithoutReachingTableSize()
    {
        $ht = new \CanisM\HashTable\HashTable();

        $dataSet = range(1, 4);

        foreach ($dataSet as $k => $v) {
            $ht->store($k, $v);
        }

        foreach ($dataSet as $k => $v) {
            $this->assertEquals($v, $ht->get($k));
        }
    }

    public function testStoreAndRetrieveByIdxWithReachingTableSize()
    {
        $ht = new \CanisM\HashTable\HashTable();

        $dataSet = range(1, 16);

        foreach ($dataSet as $k => $v) {
            $ht->store($k, $v);
        }

        foreach ($dataSet as $k => $v) {
            $this->assertEquals($v, $ht->get($k));
        }
    }

    public function testStoreAndRetrieveByStringKeyWithoutReachingTableSize()
    {
        $ht = new \CanisM\HashTable\HashTable();

        $dataSet = array_map(function () {
            return base_convert(mt_rand(10000, 999999), 10, 33);
        }, range(1, 4));

        $dataSet = array_flip($dataSet);

        foreach ($dataSet as $k => $v) {
            $ht->store($k, $v);
        }

        foreach ($dataSet as $k => $v) {
            $this->assertEquals($v, $ht->get($k));
        }
    }

    /**
     * with possible collisions and rewrites.
     */
    public function testStoreAndRetrieveByStringKeyWithReachingTableSize()
    {
        $ht = new \CanisM\HashTable\HashTable();

        $dataSet = array_map(function () {
            return base_convert(mt_rand(10000, 99999999), 10, 33);
        }, range(1, 1024));

        $dataSet = array_flip($dataSet);

        foreach ($dataSet as $k => $v) {
            $ht->store($k, $v);
        }

        foreach ($dataSet as $k => $v) {
            $this->assertEquals($v, $ht->get($k));
        }
    }

    /**
     * a few dozens of rewrites.
     */
    public function testStoreAndRewriteValue()
    {
        $ht = new \CanisM\HashTable\HashTable();

        $dataSet = array_map(function () {
            return base_convert(mt_rand(99999199, 99999999), 10, 33);
        }, range(1, 512));

        echo sprintf("\nRewrites count in %s: %d\n", __METHOD__, count($dataSet) - count(array_unique($dataSet)));

        foreach ($dataSet as $k => $v) {
            $ht->store($v, $k);
        }

        $dataSet = array_flip($dataSet);

        foreach ($dataSet as $k => $v) {
            $this->assertEquals($v, $ht->get($k));
        }
    }

    public function testAppend()
    {
        $ht = new \CanisM\HashTable\HashTable();

        $dataSet = range(1, 256);

        foreach ($dataSet as $v) {
            $ht->append($v);
        }

        foreach ($dataSet as $k => $v) {
            $this->assertEquals($v, $ht->get($k));
        }
    }

    public function testAppendMixedWithStore()
    {
        $ht = new \CanisM\HashTable\HashTable();

        $dataSet = array();

        foreach (range(1, 1024) as $o) {
            switch (mt_rand(1, 3)) {
                case 1:
                    $ht->append($dataSet[] = mt_rand(1, 9999999));
                    break;
                case 2:
                    $k = mt_rand(1, 999999);
                    $v = mt_rand(1, 999999);
                    $ht->store($k, $v);
                    $dataSet[$k] = $v;
                    break;
                case 3:
                    $k = base_convert(mt_rand(1, 99999999), 10, 33);
                    $v = mt_rand(1, 999999);
                    $ht->store($k, $v);
                    $dataSet[$k] = $v;
                    break;
            }
        }

        foreach ($dataSet as $k => $v) {
            $this->assertEquals($v, $ht->get($k));
        }
    }

    public function testLinkedOrder()
    {
        $ht = new \CanisM\HashTable\HashTable();

        $dataSet = range(1, 256);

        foreach ($dataSet as $k => $v) {
            $ht->store($k, $v);
        }

        reset($dataSet);
        while ($ht->valid()) {
            $this->assertEquals(current($dataSet), $ht->current());

            next($dataSet);
            $ht->next();
        }
    }

    public function testForeachable()
    {
        $ht = new \CanisM\HashTable\HashTable();

        $dataSet = array(
            "asd", "qwe", 5 => "ret", 6 => 8, 3, "xcv" => 54
        );

        foreach ($dataSet as $k => $v) {
            $ht->store($k, $v);
        }

        reset($dataSet);
        foreach ($ht as $k => $v) {
            $this->assertEquals(key($dataSet), $k);
            $this->assertEquals(current($dataSet), $v);

            next($dataSet);
        }
    }

    public function testRemove()
    {
        $ht = new \CanisM\HashTable\HashTable();

        $dataSet = range(0, 256);

        foreach ($dataSet as $k => $v) {
            $ht->store($k, $v);
        }

        foreach (range(0, 32) as $o) {
            $toRemove = mt_rand(0, 256);
            if (isset($dataSet[$toRemove])) {
                unset($dataSet[$toRemove]);
                $ht->remove($toRemove);
            }
        }

        foreach ($dataSet as $k => $v) {
            $this->assertEquals($v, $ht->get($k));
        }
    }

}
