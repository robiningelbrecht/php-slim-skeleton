<?php

namespace App\Tests\Controller;

use App\Controller\ChooseCoolestPokemonRequestHandler;
use App\Domain\WriteModel\Pokemon\PokedexIdGenerator;
use App\Domain\WriteModel\Pokemon\PokemonRepository;
use App\Domain\WriteModel\Vote\AddVote\AddVote;
use App\Domain\WriteModel\Vote\AddVoteCommandQueue;
use App\Domain\WriteModel\Vote\VoteId;
use App\Domain\WriteModel\Vote\VoteIdGenerator;
use App\Infrastructure\Serialization\Json;
use App\Tests\ContainerTestCase;
use App\Tests\Domain\WriteModel\Pokemon\PokemonBuilder;
use App\Tests\ProvideHttpRequest;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;
use Spatie\Snapshots\MatchesSnapshots;
use Twig\Environment;

class ChooseCoolestPokemonRequestHandlerTest extends ContainerTestCase
{
    use MatchesSnapshots;
    use ProvideHttpRequest;

    private ChooseCoolestPokemonRequestHandler $chooseCoolestPokemonRequestHandler;
    private MockObject $pokedexIdGenerator;
    private MockObject $voteIdGenerator;
    private MockObject $pokemonRepository;
    private MockObject $addVoteCommandQueue;

    public function testHandle(): void
    {
        $pokeIdOne = 3;
        $pokeIdTwo = 10;

        $this->voteIdGenerator
            ->expects($this->never())
            ->method('random');

        $this->addVoteCommandQueue
            ->expects($this->never())
            ->method('queue');

        $this->pokedexIdGenerator
            ->expects($this->exactly(2))
            ->method('random')
            ->willReturnOnConsecutiveCalls(
                $pokeIdOne,
                $pokeIdTwo,
            );

        $this->pokemonRepository
            ->expects($this->exactly(2))
            ->method('findByPokedexId')
            ->withConsecutive([3], [10])
            ->willReturn(PokemonBuilder::fromDefaults()
                ->withStats([
                    [
                        'base' => 30,
                        'name' => 'hp',
                    ],
                    [
                        'base' => 56,
                        'name' => 'attack',
                    ],
                    [
                        'base' => 10,
                        'name' => 'defense',
                    ],
                    [
                        'base' => 20,
                        'name' => 'speed',
                    ],
                ])
                ->build());

        $response = $this->chooseCoolestPokemonRequestHandler->handle(
            $this->createMock(ServerRequestInterface::class),
            new Response(200),
        );

        $this->assertMatchesHtmlSnapshot((string) $response->getBody());
    }

    public function testHandleWithPreviousIds(): void
    {
        $this->voteIdGenerator
            ->expects($this->once())
            ->method('random')
            ->willReturn(VoteId::fromString('vote-test'));

        $this->addVoteCommandQueue
            ->expects($this->once())
            ->method('queue')
            ->willReturnCallback(function (AddVote $command) {
                $this->assertMatchesJsonSnapshot(Json::encode($command));
            });

        $this->pokedexIdGenerator
            ->expects($this->never())
            ->method('random');

        $this->pokemonRepository
            ->expects($this->never())
            ->method('findByPokedexId');

        $response = $this->chooseCoolestPokemonRequestHandler->handle(
            $this->createMock(ServerRequestInterface::class),
            new Response(200),
            'pokemon-test',
            'pokemon-test2',
        );

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertMatchesJsonSnapshot(Json::encode($response->getHeaders()));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->pokedexIdGenerator = $this->createMock(PokedexIdGenerator::class);
        $this->voteIdGenerator = $this->createMock(VoteIdGenerator::class);
        $this->pokemonRepository = $this->createMock(PokemonRepository::class);
        $this->addVoteCommandQueue = $this->createMock(AddVoteCommandQueue::class);

        $this->chooseCoolestPokemonRequestHandler = new ChooseCoolestPokemonRequestHandler(
            $this->getContainer()->get(Environment::class),
            $this->pokedexIdGenerator,
            $this->voteIdGenerator,
            $this->pokemonRepository,
            $this->addVoteCommandQueue
        );
    }
}
