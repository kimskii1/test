<?php

declare(strict_types=1);

namespace App\Service;

use App\Client\Currate\CurrateClient;
use App\Entity\Currency;
use App\Entity\Rate;
use App\Exception\ClientException;
use App\Exception\ServiceException;
use App\Repository\RateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class RateService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private RateRepository $rateRepository,
        private CurrateClient $currateClient,
    ) {
    }

    public function addRate(Currency $currencyFrom, Currency $currencyTo): void
    {
        $rate = $this->rateRepository->findOneBy([
            'from' => $currencyFrom,
            'to' => $currencyTo,
        ]);
        if ($rate) {
            throw new ServiceException('Rate already exists');
        }

        $currencyPair = $currencyFrom->getCode() . $currencyTo->getCode();
        try {
            $result = $this->currateClient->getRates([$currencyPair]);
        } catch (ClientException $e) {
            throw new ServiceException($e->getMessage(), previous: $e);
        }

        $rateValue = $result[$currencyPair] ?? throw new ServiceException('Currency rate not found');

        $rate = (new Rate())
            ->setFrom($currencyFrom)
            ->setTo($currencyTo)
            ->setValue((float) $rateValue);

        $this->entityManager->persist($rate);
        $this->entityManager->flush();
    }

    public function updateAllRates(): void
    {
        $rates = $this->rateRepository->findAll();
        $ratesMap = [];
        $currencyPairs = [];
        foreach ($rates as $rate) {
            $currencyPair = $rate->getFrom()->getCode() . $rate->getTo()->getCode();
            $ratesMap[$currencyPair] = $rate;
            $currencyPairs[] = $currencyPair;
        }

        try {
            $result = $this->currateClient->getRates($currencyPairs);
        } catch (ClientException $e) {
            throw new ServiceException($e->getMessage(), previous: $e);
        }
        foreach ($result as $currencyPair => $rateValue) {
            if (isset($ratesMap[$currencyPair])) {
                $ratesMap[$currencyPair]->setValue((float) $rateValue);
            }
        }

        $this->entityManager->flush();
    }

    public function deleteRate(Currency $currencyFrom, Currency $currencyTo): void
    {
        $rate = $this->rateRepository->findOneBy([
            'from' => $currencyFrom,
            'to' => $currencyTo,
        ]) ?? throw new NotFoundHttpException('Rate not found');

        $this->entityManager->remove($rate);
        $this->entityManager->flush();
    }
}
