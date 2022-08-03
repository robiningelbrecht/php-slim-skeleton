<?php

namespace App\Domain\WriteModel\Vote\AddVote;

use App\Domain\WriteModel\Pokemon\PokemonRepository;
use App\Domain\WriteModel\Vote\Vote;
use App\Domain\WriteModel\Vote\VoteRepository;
use App\Infrastructure\Attribute\AsCommandHandler;
use App\Infrastructure\CQRS\CommandHandler;
use App\Infrastructure\CQRS\DomainCommand;

#[AsCommandHandler]
class AddVoteCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly PokemonRepository $pokemonRepository,
        private readonly VoteRepository $voteRepository
    )
    {
    }

    public function handle(DomainCommand $command): void
    {
        assert($command instanceof AddVote);

        if ($command->getPokemonVotedFor() === $command->getPokemonNotVotedFor()) {
            throw new \RuntimeException('Dirty little cheater');
        }
        $upvotedPoke = $this->pokemonRepository->findByUuid($command->getPokemonVotedFor());
        $notUpvotedPoke = $this->pokemonRepository->findByUuid($command->getPokemonNotVotedFor());

        $this->voteRepository->add(Vote::create(
            $command->getUuid(),
            $upvotedPoke->getUuid(),
            $notUpvotedPoke->getUuid()
        ));
    }

}