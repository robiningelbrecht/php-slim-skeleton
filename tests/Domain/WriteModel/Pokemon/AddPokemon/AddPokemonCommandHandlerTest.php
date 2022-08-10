<?php

namespace App\Tests\Domain\WriteModel\Pokemon\AddPokemon;

use App\Domain\WriteModel\Pokemon\AddPokemon\AddPokemon;
use App\Domain\WriteModel\Pokemon\AddPokemon\AddPokemonCommandHandler;
use App\Domain\WriteModel\Pokemon\PokemonRepository;
use App\Infrastructure\CQRS\CommandHandler\CommandHandler;
use App\Tests\CommandHandlerTestCase;
use App\Tests\Domain\WriteModel\Pokemon\PokemonBuilder;
use PHPUnit\Framework\MockObject\MockObject;

class AddPokemonCommandHandlerTest extends CommandHandlerTestCase
{
    private AddPokemonCommandHandler $addPokemonCommandHandler;
    private MockObject $pokemonRepository;

    public function testHandle(): void
    {
        $pokemon = PokemonBuilder::fromDefaults()->build();

        $this->pokemonRepository
            ->expects($this->once())
            ->method('add')
            ->with($pokemon);

        $this->addPokemonCommandHandler->handle(new AddPokemon(
            $pokemon->getPokemonId(),
            $pokemon->getPokedexId(),
            $pokemon->getName(),
            $pokemon->getBaseExperience(),
            $pokemon->getHeight(),
            $pokemon->getWeight(),
            $pokemon->getAbilities(),
            $pokemon->getMoves(),
            $pokemon->getTypes(),
            $pokemon->getStats(),
            $pokemon->getSprites()
        ));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->pokemonRepository = $this->createMock(PokemonRepository::class);

        $this->addPokemonCommandHandler = new AddPokemonCommandHandler(
            $this->pokemonRepository
        );
    }

    protected function getCommandHandler(): CommandHandler
    {
        return $this->addPokemonCommandHandler;
    }
}
