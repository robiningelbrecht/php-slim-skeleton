<?php

namespace App\Domain\WriteModel\Vote;

use App\Domain\WriteModel\Pokemon\PokemonId;
use App\Infrastructure\Eventing\AggregateRoot;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Ramsey\Uuid\UuidInterface;

#[Entity]
class Vote extends AggregateRoot
{
    private function __construct(
        #[Id, Column(type: "guid", unique: true, nullable: false)]
        private readonly UuidInterface $uuid,
        #[Column(type: "string", nullable: false)]
        private readonly PokemonId $pokemonVotedFor,
        #[Column(type: "string", nullable: false)]
        private readonly PokemonId $pokemonNotVotedFor
    )
    {
    }

    public static function create(
        UuidInterface $uuid,
        PokemonId $pokemonVotedFor,
        PokemonId $pokemonNotVotedFor
    ): self
    {
        $vote =  new self($uuid, $pokemonVotedFor, $pokemonNotVotedFor);
        $vote->recordThat(new VoteWasAdded(
            $vote->getUuid(),
            $vote->getPokemonVotedFor(),
            $vote->getPokemonNotVotedFor(),
        ));

        return $vote;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getPokemonVotedFor(): PokemonId
    {
        return $this->pokemonVotedFor;
    }

    public function getPokemonNotVotedFor(): PokemonId
    {
        return $this->pokemonNotVotedFor;
    }

    public function toArray(): array
    {
        return [
            'uuid' => (string)$this->getUuid(),
            'pokemonVotedFor' => $this->getPokemonVotedFor(),
            'pokemonNotVotedFor' => $this->getPokemonNotVotedFor(),
        ];
    }
}