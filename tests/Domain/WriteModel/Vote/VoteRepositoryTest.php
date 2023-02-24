<?php

namespace App\Tests\Domain\WriteModel\Vote;

use App\Domain\WriteModel\Pokemon\PokemonId;
use App\Domain\WriteModel\Vote\Vote;
use App\Domain\WriteModel\Vote\VoteId;
use App\Domain\WriteModel\Vote\VoteRepository;
use App\Infrastructure\Eventing\DomainEvent;
use App\Infrastructure\Eventing\EventBus;
use App\Infrastructure\Serialization\Json;
use App\Tests\DatabaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Spatie\Snapshots\MatchesSnapshots;

class VoteRepositoryTest extends DatabaseTestCase
{
    use MatchesSnapshots;

    private VoteRepository $voteRepository;
    private MockObject $eventBus;

    public function testAdd(): void
    {
        $vote = Vote::create(
            VoteId::fromString('vote-test'),
            PokemonId::fromString('pokemon-voted-for'),
            PokemonId::fromString('pokemon-not-voted-for')
        );

        $this->eventBus
            ->expects($this->once())
            ->method('publish')
            ->willReturnCallback(function (DomainEvent ...$domainEvents) {
                $this->assertMatchesJsonSnapshot(Json::encode($domainEvents));
            });

        $this->voteRepository->add($vote);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventBus = $this->createMock(EventBus::class);

        $this->voteRepository = new VoteRepository(
            $this->getConnection(),
            $this->eventBus,
        );
    }
}
