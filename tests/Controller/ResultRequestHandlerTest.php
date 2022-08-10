<?php

namespace App\Tests\Controller;

use App\Controller\ResultRequestHandler;
use App\Domain\ReadModel\Result\Result;
use App\Domain\ReadModel\Result\ResultRepository;
use App\Domain\WriteModel\Pokemon\PokemonId;
use App\Domain\WriteModel\Pokemon\PokemonRepository;
use App\Tests\ContainerTestCase;
use App\Tests\Domain\WriteModel\Pokemon\PokemonBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;
use Spatie\Snapshots\MatchesSnapshots;
use Twig\Environment;

class ResultRequestHandlerTest extends ContainerTestCase
{
    use MatchesSnapshots;

    private ResultRequestHandler $resultRequestHandler;
    private MockObject $resultRepository;
    private MockObject $pokemonRepository;

    public function testHandle(): void
    {
        $this->resultRepository
            ->expects($this->once())
            ->method('getAllWithAtLeastOneUpVote')
            ->willReturn([
                Result::fromState(
                    PokemonId::fromString('pokemon-test'),
                    10,
                    2,
                    20
                ),
                Result::fromState(
                    PokemonId::fromString('pokemon-test'),
                    2,
                    2,
                    100
                ),
            ]);

        $pokemon = PokemonBuilder::fromDefaults()
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
            ->build();

        $this->pokemonRepository
            ->expects($this->exactly(2))
            ->method('find')
            ->with(PokemonId::fromString('pokemon-test'))
            ->willReturn($pokemon);

        $response = $this->resultRequestHandler->handle(
            $this->createMock(ServerRequestInterface::class),
            new Response(200),
        );

        $this->assertMatchesHtmlSnapshot((string) $response->getBody());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->resultRepository = $this->createMock(ResultRepository::class);
        $this->pokemonRepository = $this->createMock(PokemonRepository::class);

        $this->resultRequestHandler = new ResultRequestHandler(
            $this->getContainer()->get(Environment::class),
            $this->resultRepository,
            $this->pokemonRepository
        );
    }
}
