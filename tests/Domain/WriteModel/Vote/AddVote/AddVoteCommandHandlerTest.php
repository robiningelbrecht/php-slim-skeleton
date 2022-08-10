<?php

namespace App\Tests\Domain\WriteModel\Vote\AddVote;

use App\Domain\WriteModel\Pokemon\PokemonId;
use App\Domain\WriteModel\Pokemon\PokemonRepository;
use App\Domain\WriteModel\Vote\AddVote\AddVote;
use App\Domain\WriteModel\Vote\AddVote\AddVoteCommandHandler;
use App\Domain\WriteModel\Vote\Vote;
use App\Domain\WriteModel\Vote\VoteId;
use App\Domain\WriteModel\Vote\VoteRepository;
use App\Infrastructure\CQRS\CommandHandler\CommandHandler;
use App\Tests\CommandHandlerTestCase;
use App\Tests\Domain\WriteModel\Pokemon\PokemonBuilder;
use PHPUnit\Framework\MockObject\MockObject;

class AddVoteCommandHandlerTest extends CommandHandlerTestCase
{
    private AddVoteCommandHandler $addVoteCommandHandler;
    private readonly MockObject $pokemonRepository;
    private readonly MockObject $voteRepository;

    public function testHandle(): void
    {
        $pokemonVotedFor = PokemonBuilder::fromDefaults()
            ->withPokemonId(PokemonId::fromString('pokemon-voted-for'))
            ->build();
        $pokemonNotVotedFor = PokemonBuilder::fromDefaults()
            ->withPokemonId(PokemonId::fromString('pokemon-not-voted-for'))
            ->build();

        $this->pokemonRepository
            ->expects($this->exactly(2))
            ->method('find')
            ->withConsecutive(
                [$pokemonVotedFor->getPokemonId()],
                [$pokemonNotVotedFor->getPokemonId()],
            )
            ->willReturnOnConsecutiveCalls($pokemonVotedFor, $pokemonNotVotedFor);

        $this->voteRepository
            ->expects($this->once())
            ->method('add')
            ->with(Vote::create(
                VoteId::fromString('vote-test'),
                $pokemonVotedFor->getPokemonId(),
                $pokemonNotVotedFor->getPokemonId()
            ));

        $this->addVoteCommandHandler->handle(new AddVote(
            VoteId::fromString('vote-test'),
            $pokemonVotedFor->getPokemonId(),
            $pokemonNotVotedFor->getPokemonId()
        ));
    }

    public function testHandleItShouldThrowWhenSameIds(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Dirty little cheater');

        $this->addVoteCommandHandler->handle(new AddVote(
            VoteId::fromString('vote-test'),
            PokemonId::fromString('pokemon-voted-for'),
            PokemonId::fromString('pokemon-voted-for'),
        ));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->pokemonRepository = $this->createMock(PokemonRepository::class);
        $this->voteRepository = $this->createMock(VoteRepository::class);

        $this->addVoteCommandHandler = new AddVoteCommandHandler(
            $this->pokemonRepository,
            $this->voteRepository
        );
    }

    protected function getCommandHandler(): CommandHandler
    {
        return $this->addVoteCommandHandler;
    }
}
