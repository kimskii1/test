<?php

declare(strict_types=1);

namespace App\Controller\Ssr\Main;

use App\Repository\CurrencyRepository;
use App\Repository\RateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/', name: 'default')]
class IndexController extends AbstractController
{
    public function __construct(
        private readonly RateRepository $rateRepository,
        private readonly CurrencyRepository $currencyRepository,
    ) {
    }

    public function __invoke(): Response
    {
        $rates = $this->rateRepository->findAll();
        $currencies = $this->currencyRepository->findAll();

        return $this->render('main/index.html.twig', [
            'rates' => $rates,
            'currencies' => $currencies,
        ]);
    }
}
