<?php

namespace App\Domain\WriteModel\Vote\AddVote;

use App\Domain\WriteModel\Pokemon\PokemonId;
use App\Infrastructure\CQRS\DomainCommand;
use Ramsey\Uuid\UuidInterface;

class AddVote extends DomainCommand
{
    public function __construct(
        protected UuidInterface $uuid,
        protected PokemonId $pokemonVotedFor,
        protected PokemonId $pokemonNotVotedFor,
    )
    {
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
}