<?php

namespace App\Tests\Domain\WriteModel\Pokemon;

use App\Domain\WriteModel\Pokemon\PokedexIdGenerator;
use PHPUnit\Framework\TestCase;

class PokedexIdGeneratorTest extends TestCase
{
    public function testRandom(): void
    {
        $generator = new PokedexIdGenerator();
        $this->assertIsInt($generator->random());
    }
}
