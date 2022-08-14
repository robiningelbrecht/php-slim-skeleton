<?php

namespace App\Tests\Domain\WriteModel\Pokemon;

use App\Domain\WriteModel\Pokemon\Pokemon;
use App\Domain\WriteModel\Pokemon\PokemonId;

class PokemonBuilder
{
    private PokemonId $pokemonId;
    private int $pokedexId;
    private readonly string $name;
    private readonly int $baseExperience;
    private readonly int $height;
    private readonly int $weight;
    private readonly array $abilities;
    private readonly array $moves;
    private readonly array $types;
    private array $stats;
    private array $sprites;

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
        $this->stats = [
            [
                'base' => 30,
                'name' => 'hp',
            ],
            [
                'base' => 56,
                'name' => 'attack',
            ],
        ];
        $this->sprites = [
            'other' => [
                'dream_world' => [
                    'front_female' => null,
                    'front_default' => 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/dream-world/19.svg',
                ],
            ],
        ];
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

    public function withPokemonId(PokemonId $pokemonId): self
    {
        $this->pokemonId = $pokemonId;

        return $this;
    }

    public function withPokedexId(int $pokedexId): self
    {
        $this->pokedexId = $pokedexId;

        return $this;
    }

    public function withStats(array $stats): self
    {
        $this->stats = $stats;

        return $this;
    }

    public function withSprites(array $sprites): self
    {
        $this->sprites = $sprites;

        return $this;
    }
}
