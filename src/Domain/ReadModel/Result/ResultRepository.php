<?php

namespace App\Domain\ReadModel\Result;

use App\Domain\WriteModel\Pokemon\PokemonId;
use Doctrine\DBAL\Connection;

class ResultRepository
{
    public function __construct(
        private readonly Connection $connection
    ) {
    }

    /**
     * @return \App\Domain\ReadModel\Result\Result[]
     */
    public function getAllWithAtLeastOneUpVote(): array
    {
        $query = '
                SELECT * FROM Result
                INNER JOIN Pokemon on Result.pokemonId = Pokemon.pokemonId
                WHERE upVotes > 0         
                ORDER BY score DESC, upVotes DESC, pokedexId ASC';

        return array_map(
            fn (array $result) => Result::fromState(
                PokemonId::fromString($result['pokemonId']),
                (int) $result['impressions'],
                (int) $result['upVotes'],
                (int) $result['score'],
            ),
            $this->connection->executeQuery($query)->fetchAllAssociative()
        );
    }
}
