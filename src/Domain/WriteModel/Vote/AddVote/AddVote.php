<?php

namespace App\Domain\WriteModel\Vote\AddVote;

use App\Infrastructure\CQRS\DomainCommand;
use Ramsey\Uuid\UuidInterface;

class AddVote extends DomainCommand
{
    public function __construct(
        protected UuidInterface $uuid,
        protected UuidInterface $pokemonVotedFor,
        protected UuidInterface $pokemonNotVotedFor,
    )
    {
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
}