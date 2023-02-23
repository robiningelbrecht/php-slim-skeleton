<?php

namespace App\Controller;

use App\Domain\ReadModel\Result\ResultRepository;
use App\Domain\WriteModel\Pokemon\PokemonRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;

class ResultRequestHandler
{
    public function __construct(
        private readonly Environment $twig,
        private readonly ResultRepository $resultRepository,
        private readonly PokemonRepository $pokemonRepository,
    ) {
    }

    public function handle(
        ServerRequestInterface $request,
        ResponseInterface $response): ResponseInterface
    {
        $results = $this->resultRepository->getAllWithAtLeastOneUpVote();
        foreach ($results as $result) {
            $pokemon = $this->pokemonRepository->find($result->getPokemonId());
            $result->enrichWithPokemon($pokemon);
        }

        $template = $this->twig->load('results.html.twig');
        $response->getBody()->write($template->render([
            'results' => $results,
        ]));

        return $response;
    }
}