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
            $this->find($pokemon->getId());
            throw new \RuntimeException('Trying to add an already existing entry');
        } catch (EntityNotFound) {
            $this->connection->insert('Pokemon', $pokemon->toArray());
        }
    }

    public function find(string $id): Pokemon
    {
        $query = 'SELECT * FROM Pokemon WHERE id = :id';
        if (!$result = $this->connection->executeQuery($query, ['id' => $id])->fetchAssociative()) {
            throw new EntityNotFound(sprintf('Pokemon with id %s not found', $id));
        }

        return Pokemon::fromState($result);
    }

    public function findByUuid(UuidInterface $uuid): Pokemon
    {
        $query = 'SELECT * FROM Pokemon WHERE uuid = :uuid';
        if (!$result = $this->connection->executeQuery($query, ['uuid' => (string)$uuid])->fetchAssociative()) {
            throw new EntityNotFound(sprintf('Pokemon with uuid %s not found', $uuid));
        }

        return Pokemon::fromState($result);
    }

    public function save(Pokemon $pokemon): void
    {
        $this->connection->update(
            'Pokemon',
            $pokemon->toArray(),
            ['uuid' => (string)$pokemon->getUuid()]
        );
    }

    public function truncate(): void
    {
        $this->connection->executeStatement('TRUNCATE Pokemon');
    }
}