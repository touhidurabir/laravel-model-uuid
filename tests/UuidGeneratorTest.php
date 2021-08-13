<?php

namespace Touhidurabir\ModelUuid\Tests;

use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;
use Touhidurabir\ModelUuid\UuidGenerator\Generator;

class UuidGeneratorTest extends TestCase {

    /**
     * @test
     */
    public function it_return_a_string() {

        $uuid = Generator::uuid4();

        $this->assertIsString($uuid);
    }


    /**
     * @test
     */
    public function it_return_valid_uuid() {

        $uuid = Generator::uuid4();

        $this->assertTrue(Str::isUuid($uuid));
    }


    /**
     * @test
     */
    public function it_return_unique_uuid_each_time() {

        $uuid1 = Generator::uuid4();
        $uuid2 = Generator::uuid4();

        $this->assertNotEquals($uuid1, $uuid2);
    }
}