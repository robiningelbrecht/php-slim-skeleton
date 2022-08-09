<?php

namespace App\Tests\Domain\WriteModel\Pokemon;

use App\Domain\WriteModel\Pokemon\PokemonRepository;
use App\Tests\DatabaseTestCase;

class PokemonRepositoryTest extends DatabaseTestCase
{
    private PokemonRepository $pokemonRepository;

    public function testAddAndFind(): void
    {
        $pokemon = PokemonBuilder::fromDefaults()->build();
        $this->pokemonRepository->add($pokemon);

        $persisted = $this->pokemonRepository->find($pokemon->getPokemonId());
        $this->assertEquals($persisted, $pokemon);

        $persisted = $this->pokemonRepository->findByPokedexId($pokemon->getPokedexId());
        $this->assertEquals($persisted, $pokemon);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->pokemonRepository = new PokemonRepository(
            $this->getConnection()
        );
    }
}
