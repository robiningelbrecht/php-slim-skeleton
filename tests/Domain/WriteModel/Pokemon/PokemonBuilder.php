<?php

namespace App\Tests\Domain\WriteModel\Pokemon;

use App\Domain\WriteModel\Pokemon\Pokemon;
use App\Domain\WriteModel\Pokemon\PokemonId;

class PokemonBuilder
{
    private readonly PokemonId $pokemonId;
    private readonly int $pokedexId;
    private readonly string $name;
    private readonly int $baseExperience;
    private readonly int $height;
    private readonly int $weight;
    private readonly array $abilities;
    private readonly array $moves;
    private readonly array $types;
    private readonly array $stats;
    private readonly array $sprites;

    private function __construct()
    {
        $this->pokemonId = PokemonId::fromString('pokemon-test');
        $this->pokedexId = 1;
        $this->name = 'Bulbasaur';
        $this->baseExperience = 60;
        $this->height = 10;
        $this->weight = 25;
        $this->abilities = ['abilityOne', 'abilityTwo'];
        $this->moves = ['moveOne', 'moveTwo'];
        $this->types = ['typeOne', 'typeTwo'];
        $this->stats = [];
        $this->sprites = [];
    }

    public static function fromDefaults(): self
    {
        return new self();
    }

    public function build(): Pokemon
    {
        return Pokemon::fromState(
            $this->pokemonId,
            $this->pokedexId,
            $this->name,
            $this->baseExperience,
            $this->height,
            $this->weight,
            $this->abilities,
            $this->moves,
            $this->types,
            $this->stats,
            $this->sprites,
        );
    }
}
