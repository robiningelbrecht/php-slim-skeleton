<?php

namespace App\Domain\ReadModel\Result;

use App\Domain\WriteModel\Vote\VoteWasAdded;
use App\Infrastructure\Attribute\AsEventListener;
use App\Infrastructure\Eventing\EventListener\ConventionBasedEventListener;
use App\Infrastructure\Eventing\EventListener\EventListenerType;
use Doctrine\DBAL\Connection;

#[AsEventListener(type: EventListenerType::PROJECTOR)]
class VoteResultProjector extends ConventionBasedEventListener
{
    public function __construct(
        private readonly Connection $connection
    )
    {
        parent::__construct();
    }

    public function projectVoteWasAdded(VoteWasAdded $event): void
    {
        $pokemonToProject = [$event->getPokemonVotedFor(), $event->getPokemonNotVotedFor()];
        foreach ($pokemonToProject as $pokemonId) {
            $query = '
                SELECT * FROM (
                     (SELECT COUNT(1) as impressions FROM Vote WHERE pokemonVotedFor = :pokemonId OR pokemonNotVotedFor = :pokemonId) as impressions, 
                     (SELECT COUNT(1) as upVotes FROM Vote WHERE pokemonVotedFor = :pokemonId) as upVotes 
                )';

            $result = $this->connection->executeQuery($query, ['pokemonId' => $pokemonId])->fetchAssociative();

            $query = '
                REPLACE INTO Result
                (pokemonId, impressions, upVotes, score)
                VALUES
                (:pokemonId, :impressions, :upVotes, :score)';

            $this->connection->executeStatement($query, [
                'pokemonId' => $pokemonId,
                'impressions' => $result['impressions'],
                'upVotes' => $result['upVotes'],
                'score' => round(($result['upVotes'] / $result['impressions']) * 100),
            ]);
        }
    }
}