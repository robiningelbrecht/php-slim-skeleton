<?php

namespace App\Console;

use App\Domain\WriteModel\Pokemon\AddPokemon\AddPokemon;
use App\Domain\WriteModel\Pokemon\Pokemon;
use App\Domain\WriteModel\Pokemon\PokemonId;
use App\Domain\WriteModel\Pokemon\PokemonRepository;
use App\Infrastructure\CQRS\CommandBus;
use App\Infrastructure\Exception\EntityNotFound;
use App\Infrastructure\Serialization\Json;
use GuzzleHttp\Client;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'pokemon:cache', description: 'Fetch Pokémon from PokéApi and store in DB')]
class PokemonCacheConsoleCommand extends Command
{
    public function __construct(
        private readonly PokemonRepository $pokemonRepository,
        private readonly CommandBus $commandBus,
        private readonly Client $client,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach (range(1, Pokemon::MAX_ID) as $id) {
            try {
                $this->pokemonRepository->findByPokedexId($id);
                // Pokémon is already cached, skip.
                continue;
            } catch (EntityNotFound) {
            }

            $response = $this->client->get('https://pokeapi.co/api/v2/pokemon/'.$id);
            $data = Json::decode($response->getBody()->getContents());

            $this->commandBus->dispatch(new AddPokemon(
                PokemonId::random(),
                $data['id'],
                $data['name'],
                (int) $data['base_experience'],
                (int) $data['height'],
                (int) $data['weight'],
                array_map(fn (array $ability) => $ability['ability']['name'], $data['abilities']),
                array_map(fn (array $move) => $move['move']['name'], $data['moves']),
                array_map(fn (array $type) => $type['type']['name'], $data['types']),
                array_map(fn (array $stat) => [
                    'name' => $stat['stat']['name'],
                    'base' => $stat['base_stat'],
                ], $data['stats']),
                $data['sprites'],
            ));
        }

        return Command::SUCCESS;
    }
}
