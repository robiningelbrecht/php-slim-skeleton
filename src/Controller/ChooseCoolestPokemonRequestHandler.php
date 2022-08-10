<?php

namespace App\Controller;

use App\Domain\WriteModel\Pokemon\PokedexIdGenerator;
use App\Domain\WriteModel\Pokemon\PokemonId;
use App\Domain\WriteModel\Pokemon\PokemonRepository;
use App\Domain\WriteModel\Vote\AddVote\AddVote;
use App\Domain\WriteModel\Vote\AddVoteCommandQueue;
use App\Domain\WriteModel\Vote\VoteIdGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;

class ChooseCoolestPokemonRequestHandler
{
    public function __construct(
        private readonly Environment $twig,
        private readonly PokedexIdGenerator $pokedexIdGenerator,
        private readonly VoteIdGenerator $voteIdGenerator,
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
                $this->voteIdGenerator->random(),
                PokemonId::fromString($previousPokemonUpvotedId),
                PokemonId::fromString($previousPokemonNotUpvotedId)
            ));

            // Redirect to index.
            return $response->withStatus(302)->withHeader('Location', '/');
        }

        $firstPoke = $this->pokedexIdGenerator->random();
        do {
            $secondPoke = $this->pokedexIdGenerator->random();
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
