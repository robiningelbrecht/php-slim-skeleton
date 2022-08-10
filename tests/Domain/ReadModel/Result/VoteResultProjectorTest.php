<?php

namespace App\Tests\Domain\ReadModel\Result;

use App\Domain\ReadModel\Result\VoteResultProjector;
use App\Domain\WriteModel\Pokemon\PokemonId;
use App\Domain\WriteModel\Vote\VoteWasAdded;
use App\Infrastructure\Eventing\EventListener\EventListener;
use App\Tests\EventListenerTestCase;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Result;
use PHPUnit\Framework\MockObject\MockObject;

class VoteResultProjectorTest extends EventListenerTestCase
{
    private VoteResultProjector $voteResultProjector;
    private MockObject $connection;

    public function testProjectVoteWasAdded(): void
    {
        $expectedQuery = '
                SELECT * FROM (
                     (SELECT COUNT(1) as impressions FROM Vote WHERE pokemonVotedFor = :pokemonId OR pokemonNotVotedFor = :pokemonId) as impressions, 
                     (SELECT COUNT(1) as upVotes FROM Vote WHERE pokemonVotedFor = :pokemonId) as upVotes 
                )';

        $result = $this->createMock(Result::class);
        $this->connection
            ->expects($this->exactly(2))
            ->method('executeQuery')
            ->withConsecutive(
                [$expectedQuery, ['pokemonId' => PokemonId::fromString('pokemon-voted-for')]],
                [$expectedQuery, ['pokemonId' => PokemonId::fromString('pokemon-not-voted-for')]],
            )
            ->willReturnOnConsecutiveCalls(
                $result,
                $result,
            );

        $result
            ->expects($this->exactly(2))
            ->method('fetchAssociative')
            ->willReturnOnConsecutiveCalls(
                [
                    'pokemonId' => PokemonId::fromString('pokemon-voted-for'),
                    'impressions' => 10,
                    'upVotes' => 2,
                    'score' => 20,
                ],
                null,
            );

        $this->connection
            ->expects($this->once())
            ->method('executeStatement');

        $this->voteResultProjector->notifyThat(new VoteWasAdded(
            PokemonId::fromString('pokemon-voted-for'),
            PokemonId::fromString('pokemon-not-voted-for')
        ));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->connection = $this->createMock(Connection::class);

        $this->voteResultProjector = new VoteResultProjector(
            $this->connection
        );
    }

    protected function getEventListener(): EventListener
    {
        return $this->voteResultProjector;
    }
}
