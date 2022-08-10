<?php

namespace App\Tests\Domain\WriteModel\Vote;

use App\Domain\WriteModel\Vote\VoteId;
use App\Domain\WriteModel\Vote\VoteIdGenerator;
use PHPUnit\Framework\TestCase;

class VoteIdGeneratorTest extends TestCase
{
    public function testRandom(): void
    {
        $generator = new VoteIdGenerator();
        $this->assertInstanceOf(VoteId::class, $generator->random());
    }
}
