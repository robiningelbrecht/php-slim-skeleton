<?php

namespace App\Domain\WriteModel\Pokemon\AddPokemon;

use App\Domain\WriteModel\Pokemon\PokemonId;
use App\Infrastructure\CQRS\DomainCommand;

class AddPokemon extends DomainCommand
{
    public function __construct(
        protected PokemonId $pokemonId,
        protected int $pokedexId,
        protected string $name,
        protected int $baseExperience,
        protected int $height,
        protected int $weight,
        /** @var array<string> $abilities */
        protected array $abilities,
        /** @var array<string> $moves */
        protected array $moves,
        /** @var array<string> $types */
        protected array $types,
        /** @var array<array<string>> $stats */
        protected array $stats,
        /** @var array<string> $sprites */
        protected array $sprites
    ) {
    }

    public function getPokemonId(): PokemonId
    {
        return $this->pokemonId;
    }

    public function getPokedexId(): int
    {
        return $this->pokedexId;
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

    /**
     * @return array<string>
     */
    public function getAbilities(): array
    {
        return $this->abilities;
    }

    /**
     * @return array<string>
     */
    public function getMoves(): array
    {
        return $this->moves;
    }

    /**
     * @return array<string>
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * @return array<array<string>>
     */
    public function getStats(): array
    {
        return $this->stats;
    }

    /**
     * @return array<string>
     */
    public function getSprites(): array
    {
        return $this->sprites;
    }
}
