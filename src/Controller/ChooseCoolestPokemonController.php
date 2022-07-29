<?php

namespace App\Controller;

use App\Domain\WriteModel\Pokemon\Pokemon;
use App\Domain\WriteModel\Pokemon\PokemonRepository;
use App\Domain\WriteModel\Vote\Vote;
use App\Domain\WriteModel\Vote\VoteRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;
use Slim\Routing\RouteContext;
use Twig\Environment;

class ChooseCoolestPokemonController
{
    public function __construct(
        private readonly Environment $twig,
        private readonly PokemonRepository $pokemonRepository,
        private readonly VoteRepository $voteRepository,
    )
    {
    }

    public function handle(
        ServerRequestInterface $request,
        ResponseInterface $response,
        string $previousPokemonUpvotedUuid = null,
        string $previousPokemonNotUpvotedUuid = null): ResponseInterface
    {
        if ($previousPokemonUpvotedUuid && $previousPokemonNotUpvotedUuid) {
            if ($previousPokemonUpvotedUuid === $previousPokemonNotUpvotedUuid) {
                throw new \RuntimeException('Dirty little cheater');
            }
            $upvotedPoke = $this->pokemonRepository->findByUuid(Uuid::fromString($previousPokemonUpvotedUuid));
            $notUpvotedPoke = $this->pokemonRepository->findByUuid(Uuid::fromString($previousPokemonNotUpvotedUuid));

            $this->voteRepository->add(Vote::create(
                UuidV4::uuid4(),
                $upvotedPoke->getUuid(),
                $notUpvotedPoke->getUuid()
            ));

            // Redirect to index.
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            return $response->withStatus(302)->withHeader('Location', $routeParser->urlFor('index'));
        }

        $firstPoke = mt_rand(1, Pokemon::MAX_ID);
        do {
            $secondPoke = mt_rand(1, Pokemon::MAX_ID);
        } while ($secondPoke === $firstPoke);

        $template = $this->twig->load('index.html.twig');
        $response->getBody()->write($template->render(
            [
                'pokeOne' => $this->pokemonRepository->find($firstPoke),
                'pokeTwo' => $this->pokemonRepository->find($secondPoke),
            ]
        ));

        return $response;
    }
}