<?php

namespace App\Domain\WriteModel\Vote;

use App\Infrastructure\Eventing\DomainEvent;
use Ramsey\Uuid\UuidInterface;

class VoteWasAdded extends DomainEvent
{
    public function __construct(
        private UuidInterface $voteId,
        private UuidInterface $pokemonVotedFor,
        private UuidInterface $pokemonNotVotedFor
    )
    {
    }

    public function getVoteId(): UuidInterface
    {
        return $this->voteId;
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