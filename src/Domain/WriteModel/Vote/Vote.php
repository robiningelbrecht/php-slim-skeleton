<?php

namespace App\Domain\WriteModel\Vote;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Ramsey\Uuid\UuidInterface;

#[Entity]
class Vote
{
    private function __construct(
        #[Id, Column(type: "guid", unique: true, nullable: false)]
        private UuidInterface $uuid,
        #[Column(type: "guid", nullable: false)]
        private UuidInterface $pokemonVotedFor,
        #[Column(type: "guid", nullable: false)]
        private UuidInterface $pokemonNotVotedFor
    )
    {
    }

    public static function create(
        UuidInterface $uuid,
        UuidInterface $pokemonVotedFor,
        UuidInterface $pokemonNotVotedFor
    ): self
    {
        return new self($uuid, $pokemonVotedFor, $pokemonNotVotedFor);
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getPokemonVotedFor(): UuidInterface
    {
        return $this->pokemonVotedFor;
    }

    public function getPokemonNotVotedFor(): UuidInterface
    {
        return $this->pokemonNotVotedFor;
    }

    public function toArray(): array
    {
        return [
            'uuid' => (string)$this->getUuid(),
            'pokemonVotedFor' => (string)$this->getPokemonVotedFor(),
            'pokemonNotVotedFor' => (string)$this->getPokemonNotVotedFor(),
        ];
    }
}