<?php

namespace App\Domain\WriteModel\Pokemon;

use App\Infrastructure\Exception\EntityNotFound;
use App\Infrastructure\Serialization\Json;
use Doctrine\DBAL\Connection;

class PokemonRepository
{
    public function __construct(
        private readonly Connection $connection
    ) {
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

    public function findByPokedexId(int $pokedexId): Pokemon
    {
        $query = 'SELECT * FROM Pokemon WHERE pokedexId = :pokedexId';
        if (!$result = $this->connection->executeQuery($query, ['pokedexId' => $pokedexId])->fetchAssociative()) {
            throw new EntityNotFound(sprintf('Pokemon with id %s not found', $pokedexId));
        }

        return $this->buildResult($result);
    }

    public function find(PokemonId $pokemonId): Pokemon
    {
        $query = 'SELECT * FROM Pokemon WHERE pokemonId = :pokemonId';
        if (!$result = $this->connection->executeQuery($query, ['pokemonId' => $pokemonId])->fetchAssociative()) {
            throw new EntityNotFound(sprintf('Pokemon with id %s not found', $pokemonId));
        }

        return $this->buildResult($result);
    }

    /**
     * @param array<mixed> $result
     */
    private function buildResult(array $result): Pokemon
    {
        return Pokemon::fromState(
            PokemonId::fromString($result['pokemonId']),
            $result['pokedexId'],
            $result['name'],
            (int) $result['baseExperience'],
            (int) $result['height'],
            (int) $result['weight'],
            Json::decode($result['abilities']),
            Json::decode($result['moves']),
            Json::decode($result['types']),
            Json::decode($result['stats']),
            Json::decode($result['sprites']),
        );
    }
}
