<?php

namespace App\Domain\Pokemon;

use App\Infrastructure\Exception\EntityNotFound;
use Ramsey\Uuid\UuidInterface;
use SleekDB\Store;

class PokemonRepository
{
    public function __construct(
        private readonly Store $store
    )
    {
    }

    public function add(Pokemon $pokemon): void
    {
        if ($this->store->findById($pokemon->getId())) {
            throw new \RuntimeException('Trying to add an already existing entry');
        }
        $this->store->updateOrInsert($pokemon->toArray(), false);
    }

    public function find(string $id): Pokemon
    {
        if (!$result = $this->store->findById($id)) {
            throw new EntityNotFound(sprintf('Pokemon with id %s not found', $id));
        }
        return Pokemon::fromState($result);
    }

    public function findByUuid(UuidInterface $uuid): Pokemon
    {
        if (!$result = $this->store->findOneBy(['uuid', '=', (string)$uuid])) {
            throw new EntityNotFound(sprintf('Pokemon with uuid %s not found', $uuid));
        }

        return Pokemon::fromState($result);
    }

    public function save(Pokemon $pokemon): void
    {
        $this->store->update($pokemon->toArray());
    }

    public function truncate(): void
    {
        $this->store->deleteStore();
    }
}