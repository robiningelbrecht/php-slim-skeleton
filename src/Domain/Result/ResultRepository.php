<?php

namespace App\Domain\Result;

use App\Domain\Pokemon\Pokemon;
use SleekDB\Store;

class ResultRepository
{
    public function __construct(
        private readonly Store $store
    )
    {
    }

    public function getResults(int $limit = 10): array
    {
        $results = array_map(fn(array $data) => Result::fromPokemon(Pokemon::fromState($data)), $this->store->findBy(['upVotes', '>', 0]));
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