<?php

namespace App\Console\Command;

use App\Domain\WriteModel\Pokemon\PokemonRepository;
use App\Domain\WriteModel\Vote\VoteRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'pokemon:reset', description: 'Empty complete DB, votes will be deleted as well')]
class PokemonResetConsoleCommand extends Command
{
    public function __construct(
        private readonly PokemonRepository $pokemonRepository,
        private readonly VoteRepository $voteRepository,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->pokemonRepository->truncate();
        $this->voteRepository->truncate();;
        return Command::SUCCESS;
    }
}