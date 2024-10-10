<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\CurrencyService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'currency:load:all')]
class LoadAllCurrencyCommand extends Command
{
    public function __construct(
        private readonly CurrencyService $currencyService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->currencyService->loadCurrencies();

        return Command::SUCCESS;
    }
}
