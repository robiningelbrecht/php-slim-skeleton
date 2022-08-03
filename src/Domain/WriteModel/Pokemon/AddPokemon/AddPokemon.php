<?php

namespace App\Domain\WriteModel\Pokemon\AddPokemon;

use App\Infrastructure\CQRS\DomainCommand;
use Ramsey\Uuid\UuidInterface;

class AddPokemon extends DomainCommand
{
    public function __construct(
        protected UuidInterface $uuid,
        protected string $id,
        protected string $name,
        protected int $baseExperience,
        protected int $height,
        protected int $weight,
        protected array $abilities,
        protected array $moves,
        protected array $types,
        protected array $stats,
        protected array $sprites
    )
    {
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBaseExperience(): int
    {
        return $this->baseExperience;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function getAbilities(): array
    {
        return $this->abilities;
    }

    public function getMoves(): array
    {
        return $this->moves;
    }

    public function getTypes(): array
    {
        return $this->types;
    }

    public function getStats(): array
    {
        return $this->stats;
    }

    public function getSprites(): array
    {
        return $this->sprites;
    }
}