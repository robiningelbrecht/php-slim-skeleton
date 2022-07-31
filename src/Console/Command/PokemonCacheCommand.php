<?php

namespace App\Console\Command;

use App\Domain\WriteModel\Pokemon\Pokemon;
use App\Domain\WriteModel\Pokemon\PokemonRepository;
use App\Infrastructure\Attribute\AsConsoleCommand;
use App\Infrastructure\Exception\EntityNotFound;
use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsConsoleCommand]
class PokemonCacheCommand extends Command
{

    public function __construct(
        private readonly PokemonRepository $pokemonRepository,
        private readonly Client $client,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        parent::configure();

        $this->setName('pokemon:cache');
        $this->setDescription('Fetch Pokémon from PokéApi and store in DB');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach (range(1, Pokemon::MAX_ID) as $id) {
            try {
                $this->pokemonRepository->find($id);
                // Pokémon is already cached, skip.
                continue;
            } catch (EntityNotFound) {

            }

            $response = $this->client->get('https://pokeapi.co/api/v2/pokemon/' . $id);

            $this->pokemonRepository->add(
                Pokemon::fromApi(json_decode($response->getBody()->getContents(), true))
            );
        }

        return Command::SUCCESS;
    }
}