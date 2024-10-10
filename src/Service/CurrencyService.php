<?php

declare(strict_types=1);

namespace App\Service;

use App\Client\Currate\CurrateClient;
use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class CurrencyService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CurrateClient $currateClient,
        private CurrencyRepository $currencyRepository,
    ) {
    }

    public function loadCurrencies(): void
    {
        $currencyPairList = $this->currateClient->getCurrencyList();
        $currencyMap = [];
        foreach ($currencyPairList as $currencyPairStr) {
            $currencyPair = str_split($currencyPairStr, 3);
            $currencyMap[$currencyPair[0]] = 1;
            $currencyMap[$currencyPair[1]] = 1;
        }
        $currencyList = array_keys($currencyMap);


        $dbCurrencyList = $this->currencyRepository->findAll();
        $dbCurrencyMap = [];
        foreach ($dbCurrencyList as $dbCurrency) {
            $dbCurrencyMap[$dbCurrency->getCode()] = $dbCurrency;
        }

        foreach ($currencyList as $code) {
            if (!isset($dbCurrencyMap[$code])) {
                $currency = (new Currency())
                    ->setCode($code);

                $this->entityManager->persist($currency);
            }
        }

        $this->entityManager->flush();
    }
}
