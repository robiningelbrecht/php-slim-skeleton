<?php

namespace App\Tests\Domain\ReadModel\Result;

use App\Domain\ReadModel\Result\Result;
use App\Domain\ReadModel\Result\ResultRepository;
use App\Domain\WriteModel\Pokemon\PokemonId;
use App\Domain\WriteModel\Pokemon\PokemonRepository;
use App\Tests\DatabaseTestCase;
use App\Tests\Domain\WriteModel\Pokemon\PokemonBuilder;

class ResultRepositoryTest extends DatabaseTestCase
{
    private ResultRepository $resultRepository;

    public function testGetAllWithAtLeastOneUpVote(): void
    {
        $this->getContainer()->get(PokemonRepository::class)->add(
            PokemonBuilder::fromDefaults()->build()
        );
        $this->getContainer()->get(PokemonRepository::class)->add(
            PokemonBuilder::fromDefaults()
                ->withPokemonId(PokemonId::random())
                ->withPokedexId(2)
                ->build()
        );

        $query = '
                REPLACE INTO Result
                (pokemonId, impressions, upVotes, score)
                VALUES
                (:pokemonId, :impressions, :upVotes, :score)';
        $this->getConnection()->executeStatement($query, [
            'pokemonId' => PokemonId::fromString('pokemon-test'),
            'impressions' => 10,
            'upVotes' => 2,
            'score' => 20,
        ]);

        $result = Result::fromState(
            PokemonId::fromString('pokemon-test'),
            10,
            2,
            20
        );
        $this->assertEquals(10, $result->getImpressions());
        $this->assertEquals(
            [
                $result,
            ],
            $this->resultRepository->getAllWithAtLeastOneUpVote()
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->resultRepository = new ResultRepository($this->getConnection());
    }
}
