<?php

namespace App\Domain\ReadModel\Result;

use App\Domain\WriteModel\Pokemon\Pokemon;
use Doctrine\DBAL\Connection;

class ResultRepository
{
    public function __construct(
        private readonly Connection $connection
    )
    {
    }

    /**
     * @return \App\Domain\ReadModel\Result\Result[]
     */
    public function getAllWithAtLeastOneUpVote(): array
    {
        $query = '
                SELECT * FROM Result
                INNER JOIN Pokemon on Result.pokemonUuid = Pokemon.uuid
                WHERE upVotes > 0         
                ORDER BY score DESC, upVotes DESC, id ASC';

        return array_map(
            fn(array $result) => Result::fromState($result),
            $this->connection->executeQuery($query)->fetchAllAssociative()
        );
    }
}