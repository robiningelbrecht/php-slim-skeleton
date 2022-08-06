<?php

namespace App\Controller;

use App\Domain\WriteModel\Pokemon\Pokemon;
use App\Domain\WriteModel\Pokemon\PokemonId;
use App\Domain\WriteModel\Pokemon\PokemonRepository;
use App\Domain\WriteModel\Vote\AddVote\AddVote;
use App\Domain\WriteModel\Vote\AddVoteCommandQueue;
use App\Domain\WriteModel\Vote\VoteId;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use Twig\Environment;

class ChooseCoolestPokemonController
{
    public function __construct(
        private readonly Environment $twig,
        private readonly PokemonRepository $pokemonRepository,
        private readonly AddVoteCommandQueue $addVoteCommandQueue
    ) {
    }

    public function handle(
        ServerRequestInterface $request,
        ResponseInterface $response,
        string $previousPokemonUpvotedId = null,
        string $previousPokemonNotUpvotedId = null): ResponseInterface
    {
        if ($previousPokemonUpvotedId && $previousPokemonNotUpvotedId) {
            $this->addVoteCommandQueue->queue(new AddVote(
                VoteId::random(),
                PokemonId::fromString($previousPokemonUpvotedId),
                PokemonId::fromString($previousPokemonNotUpvotedId)
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
                'pokeOne' => $this->pokemonRepository->findByPokedexId($firstPoke),
                'pokeTwo' => $this->pokemonRepository->findByPokedexId($secondPoke),
            ]
        ));

        return $response;
    }
}
