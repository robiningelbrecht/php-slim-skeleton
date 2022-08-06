<?php

namespace App\Tests\Infrastructure\ValueObject;

use PHPUnit\Framework\TestCase;

class IdentifierTest extends TestCase
{
    public function testItShouldCastToAndFromString()
    {
        $testIdentifier = TestIdentifier::random();
        static::assertEquals(TestIdentifier::fromString($testIdentifier), (string) $testIdentifier);
    }
}
