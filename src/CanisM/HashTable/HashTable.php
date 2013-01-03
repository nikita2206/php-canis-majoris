<?php

namespace CanisM\HashTable;

class HashTable implements \Iterator, \Countable
{

    /**
     * The size of an array that holds all the buckets
     * we have.
     *
     * @var int
     */
    private $tableSize = 4;

    /**
     * $this->tableSize - 1
     * This mask is used to adjust numeric keys for using
     * them as indexes in fixed array.
     *
     * @var int
     */
    private $keyMask = 3;

    /**
     * Number of elements currently stored in this hash
     * table.
     *
     * @var int
     */
    private $numberOfElements = 0;

    /**
     * Next free key which is used when you append an
     * element to this hash table. (e.g.: $ht[] = 5;)
     *
     * @var int
     */
    private $nextFreeKey = 0;

    /**
     * Points to the current bucket (used in iteration).
     *
     * @var null|Bucket
     */
    private $currentBucket;

    /**
     * Points to the first bucket in the linked list.
     *
     * @var null|Bucket
     */
    private $headBucket;

    /**
     * Points to the last bucket in the linked list.
     *
     * @var null|Bucket
     */
    private $tailBucket;

    /**
     * Storage of all the buckets that we have so far.
     *
     * @var \SplFixedArray|Bucket[]
     */
    private $buckets;


    public function __construct()
    {
        $this->buckets = new \SplFixedArray($this->tableSize);
    }

    /**
     * Add an element to the hash table using
     * specified key.
     *
     * @param string|int $key
     * @param mixed $value
     */
    public function store($key, $value)
    {
        $index = $this->hashKey($key);

        if ($bucket = $this->findBucket($index, $key)) {
            $bucket->setData($value);
            return;
        }

        if ((is_numeric($key)) && (int)$key >= $this->nextFreeKey) {
            $this->nextFreeKey = (int)$key + 1;
        }

        $this->createNewBucket($index, $key, $value);
    }

    /**
     * Appends element to the hash table.
     *
     * @param $value
     */
    public function append($value)
    {
        $this->store($this->nextFreeKey, $value);
    }

    /**
     * Check whether the key exists in the hash table or not.
     *
     * @param $key
     * @return bool
     */
    public function exists($key)
    {
        return null !== $this->findBucket($this->hashKey($key), $key);
    }

    /**
     * @param $key
     * @return mixed|null
     * @throws HashTableException
     */
    public function get($key)
    {
        if (null !== $bucket = $this->findBucket($this->hashKey($key), $key)) {
            return $bucket->getData();
        }

        throw HashTableException::elementNotFound($key);
    }

    /**
     * @param $key
     * @throws HashTableException
     */
    public function remove($key)
    {
        $index = $this->hashKey($key);

        if (isset($this->buckets[$index])) {
            $bucket = $this->buckets[$index];
            $previousCollidedBucket = null;

            do {
                if ($bucket->getKey() == $key) {

                    // if this is a first bucket in the collided list
                    if ($previousCollidedBucket === null) {
                        // if this is not the only one bucket with that index
                        if ($bucket->getNextCollidedBucket() !== null) {
                            $this->buckets[$index] = $bucket->getNextCollidedBucket();
                        } else {
                            unset($this->buckets[$index]);
                        }
                    }

                    $this->detachBucket($bucket, $previousCollidedBucket);

                    return;
                }
                $previousCollidedBucket = $bucket;
            } while (null !== $bucket = $bucket->getNextCollidedBucket());
        }

        throw HashTableException::elementNotFound($key);
    }

    public function current()
    {
        return $this->currentBucket === null ? false : $this->currentBucket->getData();
    }

    public function next()
    {
        if ($this->currentBucket === null) {
            $this->currentBucket = $this->headBucket;
        } else {
            $this->currentBucket = $this->currentBucket->getNextLinkedBucket();
        }
    }

    public function key()
    {
        return $this->currentBucket === null ? false : $this->currentBucket->getKey();
    }

    public function valid()
    {
        return $this->currentBucket !== null;
    }

    public function rewind()
    {
        $this->currentBucket = $this->headBucket;
    }

    public function count()
    {
        return $this->numberOfElements;
    }


    /**
     * Creates new bucket by specified index and with
     * specified key, and allocates it in the buckets array.
     *
     * @param $index
     * @param $key
     * @param $data
     * @return Bucket
     */
    private function createNewBucket($index, $key, $data = null)
    {
        $this->numberOfElements++;

        $collidedBucket = isset($this->buckets[$index]) ? $this->buckets[$index] : null;

        $this->buckets[$index] = $bucket = new Bucket($key, $data);

        if ($collidedBucket !== null) {
            $bucket->setNextCollidedBucket($collidedBucket);
        }

        $this->attachBucketToDLList($bucket);

        if ($this->numberOfElements > $this->tableSize) {
            $this->increaseHolderAndRehash();
        }

        return $bucket;
    }

    /**
     * Attaches given bucket to the doubly linked list and
     * does the work with tail and head of the hash table.
     *
     * @param Bucket $bucket
     */
    private function attachBucketToDLList(Bucket $bucket)
    {
        if ($this->tailBucket !== null) {
            $this->tailBucket->setNextLinkedBucket($bucket);
            $bucket->setPrevLinkedBucket($this->tailBucket);
        }

        $this->tailBucket = $bucket;

        if ($this->numberOfElements === 1) {
            $this->headBucket = $bucket;
            $this->currentBucket = $bucket;
        }
    }

    /**
     * Detaches bucket from head, tail, current "pointers" and
     * "glues" collided buckets. Also detaches all the data from
     * the bucket itself (avoiding memleaks).
     *
     * @param Bucket $bucket
     * @param Bucket|null $previousCollidedBucket
     */
    private function detachBucket($bucket, $previousCollidedBucket = null)
    {
        if ($this->headBucket === $bucket) {
            $this->headBucket = $bucket->getNextLinkedBucket();
        }

        if ($this->tailBucket === $bucket) {
            $this->tailBucket = $bucket->getPrevLinkedBucket();
        }

        if ($this->currentBucket === $bucket) {
            $this->currentBucket = $bucket->getNextLinkedBucket();
        }

        if ($bucket->getPrevLinkedBucket()) {
            if ($bucket->getNextLinkedBucket()) {
                $bucket->getPrevLinkedBucket()->setNextLinkedBucket($bucket->getNextLinkedBucket());
            }
        }

        if ($previousCollidedBucket !== null) {
            if ($bucket->getNextCollidedBucket() !== null) {
                $previousCollidedBucket->setNextCollidedBucket($bucket->getNextCollidedBucket());
            } else {
                $previousCollidedBucket->setNextCollidedBucket(null);
            }
        }

        $bucket->setNextCollidedBucket(null);
        $bucket->setNextLinkedBucket(null);
        $bucket->setPrevLinkedBucket(null);
        $bucket->setData(null);

        $this->numberOfElements--;
    }

    /**
     * Increases holder (multiplies its size by 2).
     * Called every time the new element inserted and the number
     * of elements is greater than the table size.
     */
    private function increaseHolderAndRehash()
    {
        $this->tableSize *= 2;
        $this->keyMask = $this->tableSize - 1;

        $this->buckets = new \SplFixedArray($this->tableSize);

        $bucket = $this->headBucket;
        do {
            $bucket->setNextCollidedBucket(null);
            $index = $this->hashKey($bucket->getKey());

            if (isset($this->buckets[$index])) {
                $bucket->setNextCollidedBucket($this->buckets[$index]);
            }

            $this->buckets[$index] = $bucket;

        } while (null !== $bucket = $bucket->getNextLinkedBucket());
    }

    private function hashKey($key)
    {
        return (is_numeric($key)) && $key >= 0 ?
            $this->hashIntegerKey((int)$key) : $this->hashStringKey($key);
    }

    /**
     * Adjusts integer so it can be used as an index
     * in our buckets array.
     *
     * @param $key
     * @return int
     */
    private function hashIntegerKey($key)
    {
        return $key & $this->keyMask;
    }

    /**
     * This is an implementation of DJBX33A hash algorithm.
     *
     * @param $key
     * @return int
     */
    private function hashStringKey($key)
    {
        $key = (string)$key;
        $hash = 5381;

        for ($len = strlen($key), $i = 0; $i < $len; $i++) {
            $hash = $hash * 33 + ord($key[$i]);
        }

        return $this->hashIntegerKey($hash);
    }

    /**
     * @param $index
     * @param $key
     * @return Bucket|null
     */
    private function findBucket($index, $key)
    {
        if (!isset($this->buckets[$index])) {
            return null;
        }

        $bucket = $this->buckets[$index];
        do {
            if ($bucket->getKey() == $key) {
                return $bucket;
            }
        } while (null !== $bucket = $bucket->getNextCollidedBucket());

        return null;
    }

    public function toArray()
    {
        $ret = array();
        $bucket = $this->headBucket;
        do {
            $ret[$bucket->getKey()] = $bucket->getData();
        } while (null !== $bucket = $bucket->getNextLinkedBucket());

        return $ret;
    }

}
