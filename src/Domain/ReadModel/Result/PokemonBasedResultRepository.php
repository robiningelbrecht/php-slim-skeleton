<?php

namespace App\Domain\ReadModel\Result;

use App\Domain\WriteModel\Pokemon\Pokemon;
use Doctrine\DBAL\Connection;

class PokemonBasedResultRepository
{
    public function __construct(
        private readonly Connection $connection
    )
    {
    }

    public function getResults(int $limit = 10): array
    {
        $query = 'SELECT * FROM Pokemon WHERE upVotes > 0';
        $results = array_map(
            fn(array $data) => Result::fromPokemon(Pokemon::fromState($data)),
            $this->connection->executeQuery($query)->fetchAllAssociative()
        );

        usort($results, function (Result $a, Result $b) {
            if ($a->getScore() === $b->getScore()) {
                if ($a->getNumberOfVotes() === $b->getNumberOfVotes()) {
                    return ($a->getPokemon()->getId() > $b->getPokemon()->getId()) ? 1 : -1;
                }

                return ($a->getNumberOfVotes() > $b->getNumberOfVotes()) ? -1 : 1;
            }

            return ($a->getScore() > $b->getScore()) ? -1 : 1;
        });

        return array_slice($results, 0, $limit);
    }
}