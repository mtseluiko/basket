<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ExampleTest extends TestCase
{
    public function testExample()
    {
        $a = Uuid::uuid1();
        $this->assertEquals(1, 1);
    }
}