<?php

namespace App\Domain\ReadModel\Result;

use App\Domain\WriteModel\Pokemon\Pokemon;
use App\Domain\WriteModel\Pokemon\PokemonId;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;

#[Entity]
class Result
{
    private ?Pokemon $pokemon = null;

    private function __construct(
        #[Id, Column(type: "string", unique: true, nullable: false)]
        private readonly PokemonId $pokemonId,
        #[Column(type: 'integer', nullable: false)]
        private readonly int $impressions,
        #[Column(type: 'integer', nullable: false)]
        private readonly int $upVotes,
        #[Column(type: 'integer', nullable: false)]
        private readonly int $score,
    )
    {
    }

    public function getPokemonId(): PokemonId
    {
        return $this->pokemonId;
    }

    public function getImpressions(): int
    {
        return $this->impressions;
    }

    public function getUpVotes(): int
    {
        return $this->upVotes;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function getPokemon(): ?Pokemon
    {
        return $this->pokemon;
    }

    public function enrichWithPokemon(Pokemon $pokemon): void
    {
        $this->pokemon = $pokemon;
    }

    public static function fromState(array $result): self
    {
        return new self(
            PokemonId::fromString($result['pokemonId']),
            (int)$result['impressions'],
            (int)$result['upVotes'],
            (int)$result['score'],
        );
    }
}