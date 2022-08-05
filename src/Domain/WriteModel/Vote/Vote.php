<?php

namespace App\Domain\WriteModel\Vote;

use App\Domain\WriteModel\Pokemon\PokemonId;
use App\Infrastructure\Eventing\AggregateRoot;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;

#[Entity]
class Vote extends AggregateRoot
{
    private function __construct(
        #[Id, Column(type: "string", unique: true, nullable: false)]
        private readonly VoteId $voteId,
        #[Column(type: "string", nullable: false)]
        private readonly PokemonId $pokemonVotedFor,
        #[Column(type: "string", nullable: false)]
        private readonly PokemonId $pokemonNotVotedFor
    )
    {
    }

    public static function create(
        VoteId $voteId,
        PokemonId $pokemonVotedFor,
        PokemonId $pokemonNotVotedFor
    ): self
    {
        $vote =  new self($voteId, $pokemonVotedFor, $pokemonNotVotedFor);
        $vote->recordThat(new VoteWasAdded(
            $vote->getVoteId(),
            $vote->getPokemonVotedFor(),
            $vote->getPokemonNotVotedFor(),
        ));

        return $vote;
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

    public function toArray(): array
    {
        return [
            'voteId' => $this->getVoteId(),
            'pokemonVotedFor' => $this->getPokemonVotedFor(),
            'pokemonNotVotedFor' => $this->getPokemonNotVotedFor(),
        ];
    }
}