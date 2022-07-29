<?php

if (php_sapi_name() !== 'cli') {
    exit;
}

use Ahc\Cli\Application;
use App\Domain\Pokemon\Pokemon;
use App\Domain\Pokemon\PokemonRepository;
use App\Infrastructure\Container;
use GuzzleHttp\Client;

require __DIR__ . '/vendor/autoload.php';

$container = Container::build();
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
    });

$app->handle($_SERVER['argv']);
