<?php

namespace App\Tests\Domain\WriteModel\Pokemon;

use App\Domain\WriteModel\Pokemon\PokemonId;
use App\Domain\WriteModel\Pokemon\PokemonRepository;
use App\Infrastructure\Exception\EntityNotFound;
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
    }

    public function testAddAndFindByPokedexId(): void
    {
        $pokemon = PokemonBuilder::fromDefaults()->build();
        $this->pokemonRepository->add($pokemon);

        $persisted = $this->pokemonRepository->findByPokedexId($pokemon->getPokedexId());
        $this->assertEquals($persisted, $pokemon);
    }

    public function testAddItShouldThrowOnDuplicates(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Trying to add an already existing entry');

        $pokemon = PokemonBuilder::fromDefaults()->build();
        $this->pokemonRepository->add($pokemon);
        $this->pokemonRepository->add($pokemon);
    }

    public function testFindByPokedexIdItShouldThrowWhenNotFound(): void
    {
        $this->expectException(EntityNotFound::class);
        $this->expectExceptionMessage('Pokemon with id 1 not found');

        $this->pokemonRepository->findByPokedexId(1);
    }

    public function testFindItShouldThrowWhenNotFound(): void
    {
        $this->expectException(EntityNotFound::class);
        $this->expectExceptionMessage('Pokemon with id pokemon-1 not found');

        $this->pokemonRepository->find(PokemonId::fromString('pokemon-1'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->pokemonRepository = new PokemonRepository(
            $this->getConnection()
        );
    }
}
