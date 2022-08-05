<?php

namespace App\Domain\WriteModel\Vote;

use App\Domain\WriteModel\Pokemon\PokemonId;
use App\Infrastructure\Eventing\DomainEvent;

class VoteWasAdded extends DomainEvent
{
    public function __construct(
        private VoteId $voteId,
        private PokemonId $pokemonVotedFor,
        private PokemonId $pokemonNotVotedFor
    )
    {
    }

    public function getVoteId(): VoteId
    {
        return $this->voteId;
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