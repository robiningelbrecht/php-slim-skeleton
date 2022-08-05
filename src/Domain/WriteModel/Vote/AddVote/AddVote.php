<?php

namespace App\Domain\WriteModel\Vote\AddVote;

use App\Domain\WriteModel\Pokemon\PokemonId;
use App\Domain\WriteModel\Vote\VoteId;
use App\Infrastructure\CQRS\DomainCommand;

class AddVote extends DomainCommand
{
    public function __construct(
        protected VoteId $voteId,
        protected PokemonId $pokemonVotedFor,
        protected PokemonId $pokemonNotVotedFor,
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