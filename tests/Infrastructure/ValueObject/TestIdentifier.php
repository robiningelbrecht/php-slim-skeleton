<?php

namespace App\Tests\Infrastructure\ValueObject;

use App\Infrastructure\ValueObject\Identifier;

readonly class TestIdentifier extends Identifier
{
    public static function getPrefix(): string
    {
        return 'test-';
    }
}
