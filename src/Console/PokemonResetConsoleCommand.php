<?php

namespace App\Console;

use App\Domain\WriteModel\Pokemon\PokemonRepository;
use App\Domain\WriteModel\Vote\VoteRepository;
use App\Infrastructure\Attribute\AsConsoleCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsConsoleCommand]
class PokemonResetConsoleCommand extends Command
{
    public function __construct(
        private readonly PokemonRepository $pokemonRepository,
        private readonly VoteRepository $voteRepository,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        parent::configure();

        $this->setName('pokemon:reset');
        $this->setDescription('Empty complete DB, votes will be deleted as well');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->pokemonRepository->truncate();
        $this->voteRepository->truncate();;
        return Command::SUCCESS;
    }
}