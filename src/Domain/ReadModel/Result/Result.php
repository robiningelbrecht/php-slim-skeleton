<?php

namespace App\Domain\ReadModel\Result;

use App\Domain\WriteModel\Pokemon\Pokemon;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[Entity]
class Result
{
    private ?Pokemon $pokemon = null;

    private function __construct(
        #[Id, Column(type: "guid", unique: true, nullable: false)]
        private readonly UuidInterface $pokemonUuid,
        #[Column(type: 'integer', nullable: false)]
        private readonly int $impressions,
        #[Column(type: 'integer', nullable: false)]
        private readonly int $upVotes,
        #[Column(type: 'integer', nullable: false)]
        private readonly int $score,
    )
    {
    }

    public function getPokemonUuid(): UuidInterface
    {
        return $this->pokemonUuid;
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
            Uuid::fromString($result['pokemonUuid']),
            (int)$result['impressions'],
            (int)$result['upVotes'],
            (int)$result['score'],
        );
    }
}