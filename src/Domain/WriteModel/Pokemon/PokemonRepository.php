<?php

namespace App\Domain\WriteModel\Pokemon;

use App\Infrastructure\Exception\EntityNotFound;
use Doctrine\DBAL\Connection;
use Ramsey\Uuid\UuidInterface;

class PokemonRepository
{
    public function __construct(
        private readonly Connection $connection
    )
    {
    }

    public function add(Pokemon $pokemon): void
    {
        try {
            $this->findByPokedexId($pokemon->getPokedexId());
            throw new \RuntimeException('Trying to add an already existing entry');
        } catch (EntityNotFound) {
            $this->connection->insert('Pokemon', $pokemon->toArray());
        }
    }

    public function findByPokedexId(string $pokedexId): Pokemon
    {
        $query = 'SELECT * FROM Pokemon WHERE pokedexId = :pokedexId';
        if (!$result = $this->connection->executeQuery($query, ['pokedexId' => $pokedexId])->fetchAssociative()) {
            throw new EntityNotFound(sprintf('Pokemon with id %s not found', $pokedexId));
        }

        return Pokemon::fromState($result);
    }

    public function find(PokemonId $pokemonId): Pokemon
    {
        $query = 'SELECT * FROM Pokemon WHERE pokemonId = :pokemonId';
        if (!$result = $this->connection->executeQuery($query, ['pokemonId' => $pokemonId])->fetchAssociative()) {
            throw new EntityNotFound(sprintf('Pokemon with id %s not found', $pokemonId));
        }

        return Pokemon::fromState($result);
    }

    public function save(Pokemon $pokemon): void
    {
        $this->connection->update(
            'Pokemon',
            $pokemon->toArray(),
            ['pokemonId' => $pokemon->getPokemonId()]
        );
    }
}