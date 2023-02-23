<?php

namespace App\Tests\Console;

use App\Console\PokemonCacheConsoleCommand;
use App\Domain\WriteModel\Pokemon\PokemonRepository;
use App\Infrastructure\CQRS\CommandBus;
use App\Infrastructure\Exception\EntityNotFound;
use App\Tests\ConsoleCommandTestCase;
use App\Tests\Domain\WriteModel\Pokemon\PokemonBuilder;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class PokemonCacheConsoleCommandTest extends ConsoleCommandTestCase
{
    private PokemonCacheConsoleCommand $pokemonCacheConsoleCommand;
    private MockObject $pokemonRepository;
    private MockObject $commandBus;
    private MockObject $client;

    public function testExecute(): void
    {
        $matcher = $this->exactly(251);
        $this->pokemonRepository
            ->expects($matcher)
            ->method('findByPokedexId')
            ->willReturnCallback(function () use ($matcher) {
                if ($matcher->numberOfInvocations() <= 2) {
                    throw new EntityNotFound();
                }

                return PokemonBuilder::fromDefaults()->build();
            });

        $this->client
            ->expects($this->exactly(2))
            ->method('get')
            ->willReturnCallback(function (string $uri) use ($matcher) {
                match ($matcher->numberOfInvocations()) {
                    1 => $this->assertEquals($uri, 'https://pokeapi.co/api/v2/pokemon/1'),
                    2 => $this->assertEquals($uri, 'https://pokeapi.co/api/v2/pokemon/2'),
                };
            })
            ->willReturnOnConsecutiveCalls(
                new Response(200, [], $this->getMockResponse()),
                new Response(200, [], $this->getMockResponse())
            );

        $this->commandBus
            ->expects($this->exactly(2))
            ->method('dispatch');

        $command = $this->getCommandInApplication('app:pokemon:cache');

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->pokemonRepository = $this->createMock(PokemonRepository::class);
        $this->commandBus = $this->createMock(CommandBus::class);
        $this->client = $this->createMock(Client::class);

        $this->pokemonCacheConsoleCommand = new PokemonCacheConsoleCommand(
            $this->pokemonRepository,
            $this->commandBus,
            $this->client
        );
    }

    protected function getConsoleCommand(): Command
    {
        return $this->pokemonCacheConsoleCommand;
    }

    private function getMockResponse(): string
    {
        return '{"abilities":[{"ability":{"name":"overgrow","url":"https:\/\/pokeapi.co\/api\/v2\/ability\/65\/"},"is_hidden":false,"slot":1}],"moves":[{"move":{"name":"razor-wind","url":"https:\/\/pokeapi.co\/api\/v2\/move\/13\/"}}],"types":[{"slot":1,"type":{"name":"grass","url":"https:\/\/pokeapi.co\/api\/v2\/type\/12\/"}}],"stats":[{"base_stat":45,"effort":0,"stat":{"name":"hp","url":"https:\/\/pokeapi.co\/api\/v2\/stat\/1\/"}}],"sprites":{"other":{"dream_world":{"front_default":"https:\/\/raw.githubusercontent.com\/PokeAPI\/sprites\/master\/sprites\/pokemon\/other\/dream-world\/1.svg","front_female":null}}},"base_experience":64,"height":7,"id":1,"name":"bulbasaur","weight":10}';
    }
}
