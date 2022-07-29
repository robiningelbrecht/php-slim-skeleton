<?php

namespace App\Domain\ReadModel\Result;

use App\Domain\WriteModel\Pokemon\Pokemon;
use Doctrine\DBAL\Connection;

class VoteBasedResultRepository
{
    public function __construct(
        private readonly Connection $connection
    )
    {
    }

    public function getResults(int $limit = 10): array
    {
        $query = '
        SELECT Pokemon.*,
        (SELECT COUNT(1) FROM Vote WHERE pokemonVotedFor = Pokemon.uuid OR pokemonNotVotedFor = Pokemon.uuid) as impressions, 
        (SELECT COUNT(1) FROM Vote WHERE pokemonVotedFor = Pokemon.uuid) as upVotes 
        FROM Pokemon
        WHERE EXISTS(SELECT pokemonVotedFor FROM Vote WHERE pokemonVotedFor = Pokemon.uuid)
        ORDER BY upVotes / impressions DESC, upVotes DESC, id ASC';

        $results = array_map(
            fn(array $data) => Result::fromPokemonAndVotes(Pokemon::fromState($data), $data['impressions'], $data['upVotes']),
            $this->connection->executeQuery($query)->fetchAllAssociative()
        );

        return array_slice($results, 0, $limit);
    }
}