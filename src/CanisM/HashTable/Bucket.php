<?php

namespace CanisM\HashTable;

/**
 * Bucket basically is just a data holder.
 * Each bucket represents each element in the array.
 */
class Bucket
{

    /**
     * Original key of this element.
     *
     * @var int|string
     */
    private $key;

    /**
     * Element itself.
     *
     * @var mixed
     */
    private $data;

    /**
     * Previous element, used for ordering arrays.
     *
     * @var Bucket|null
     */
    private $prevLinkedBucket;

    /**
     * @var Bucket|null
     */
    private $nextLinkedBucket;

    /**
     * Next element that have the same hash of the key.
     * (read #SeparateChaining in http://en.wikipedia.org/wiki/Hash_table)
     *
     * @var Bucket|null
     */
    private $nextCollidedBucket;


    public function __construct($key, $data = null)
    {
        $this->key = $key;
        $this->data = $data;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getNextCollidedBucket()
    {
        return $this->nextCollidedBucket;
    }

    public function setNextCollidedBucket($bucket)
    {
        $this->nextCollidedBucket = $bucket;
    }

    public function getNextLinkedBucket()
    {
        return $this->nextLinkedBucket;
    }

    public function setNextLinkedBucket($bucket)
    {
        return $this->nextLinkedBucket = $bucket;
    }

    public function getPrevLinkedBucket()
    {
        return $this->prevLinkedBucket;
    }

    public function setPrevLinkedBucket($bucket)
    {
        return $this->prevLinkedBucket = $bucket;
    }

}
