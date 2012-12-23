<?php

namespace CanisM\Test;

class ArrayHtTest extends \PHPUnit_Framework_TestCase
{

    public function testOffsetSet()
    {
        $ar = new \CanisM\ArrayHt();

        $ar["asd"] = "asd";

        $this->assertEquals("asd", $ar["asd"]);
    }

    public function testIterate()
    {
        $ar = new \CanisM\ArrayHt();

        $dataSet = range(1, 64);

        foreach ($dataSet as $v) {
            $ar[] = $v;
        }

        foreach ($ar as $k => $v) {
            $this->assertEquals($dataSet[$k], $v);
        }
    }

}