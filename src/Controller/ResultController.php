<?php

namespace App\Controller;

use App\Domain\Pokemon\Pokemon;
use App\Domain\Pokemon\PokemonRepository;
use App\Domain\Result\ResultRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;

class ResultController
{
    public function __construct(
        private readonly Environment $twig,
        private readonly ResultRepository $resultRepository,
    )
    {
    }

    public function handle(
        ServerRequestInterface $request,
        ResponseInterface $response): ResponseInterface
    {
        $template = $this->twig->load('results.html.twig');
        $response->getBody()->write($template->render([
            'results' => $this->resultRepository->getResults(Pokemon::MAX_ID)
        ]));

        return $response;
    }
}