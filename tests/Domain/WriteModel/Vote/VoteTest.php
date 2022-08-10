<?php

namespace App\Tests\Domain\WriteModel\Vote;

use App\Domain\WriteModel\Pokemon\PokemonId;
use App\Domain\WriteModel\Vote\Vote;
use App\Domain\WriteModel\Vote\VoteId;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class VoteTest extends TestCase
{
    use MatchesSnapshots;

    public function testCreateItShouldRecordEvent(): void
    {
        $vote = Vote::create(
            VoteId::fromString('vote-test'),
            PokemonId::fromString('pokemon-voted-for'),
            PokemonId::fromString('pokemon-not-voted-for')
        );

        $this->assertMatchesJsonSnapshot($vote->getRecordedEvents());
    }

    public function testToArray(): void
    {
        $vote = Vote::create(
            VoteId::fromString('vote-test'),
            PokemonId::fromString('pokemon-voted-for'),
            PokemonId::fromString('pokemon-not-voted-for')
        );

        $this->assertMatchesJsonSnapshot($vote->toArray());
    }
}
