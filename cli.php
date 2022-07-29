<?php

if (php_sapi_name() !== 'cli') {
    exit;
}

use Ahc\Cli\Application;
use App\Domain\WriteModel\Pokemon\Pokemon;
use App\Domain\WriteModel\Pokemon\PokemonRepository;
use App\Domain\WriteModel\Vote\VoteRepository;

use GuzzleHttp\Client;

require __DIR__ . '/vendor/autoload.php';

$container = require_once __DIR__ . '/bootstrap.php';
$app = new Application('The coolest Pokémon', '0.0.1');

$app
    ->command('pokemon:cache', 'Fetch Pokémon from PokéApi and store in DB')
    ->action(function () use ($container) {
        $client = new Client();
        $pokemonRepository = $container->get(PokemonRepository::class);

        foreach (range(1, Pokemon::MAX_ID) as $id) {
            $response = $client->get('https://pokeapi.co/api/v2/pokemon/' . $id);

            try {
                $pokemonRepository->add(
                    Pokemon::fromApi(json_decode($response->getBody()->getContents(), true))
                );
            } catch (RuntimeException) {
                // Pokémon was already cached, ignore.
            }

        }
    })
    ->tap()
    ->command('pokemon:reset', 'Empty complete DB, votes will be deleted as well')
    ->action(function () use ($container) {
        $pokemonRepository = $container->get(PokemonRepository::class);
        $pokemonRepository->truncate();
        $voteRepository = $container->get(VoteRepository::class);
        $voteRepository->truncate();;
    });

$app->handle($_SERVER['argv']);
