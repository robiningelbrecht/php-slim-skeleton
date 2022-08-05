<?php

namespace App\Controller;

use App\Domain\ReadModel\Result\ResultRepository;
use App\Domain\ReadModel\Result\VoteBasedResultRepository;
use App\Domain\WriteModel\Pokemon\Pokemon;
use App\Domain\WriteModel\Pokemon\PokemonRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;

class ResultController
{
    public function __construct(
        private readonly Environment $twig,
        private readonly ResultRepository $resultRepository,
        private readonly PokemonRepository $pokemonRepository,
    )
    {
    }

    public function handle(
        ServerRequestInterface $request,
        ResponseInterface $response): ResponseInterface
    {
        $results = $this->resultRepository->getAllWithAtLeastOneUpVote();
        foreach ($results as $result) {
            $pokemon = $this->pokemonRepository->findByUuid($result->getPokemonUuid());
            $result->enrichWithPokemon($pokemon);
        }

        $template = $this->twig->load('results.html.twig');
        $response->getBody()->write($template->render([
            'results' => $results,
        ]));

        return $response;
    }
}