<?php

declare(strict_types=1);

namespace App\Controller\Api\V1\Rate\Create;

use App\Exception\ServiceException;
use App\Repository\CurrencyRepository;
use App\Service\RateService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route(path: '/api/v1/rate', name: 'rate.create', methods: ['POST'])]
final readonly class CreateRateController
{
    public function __construct(
        private CurrencyRepository $currencyRepository,
        private RateService  $rateService,
    ) {
    }

    public function __invoke(#[MapRequestPayload] CreateRateRequest $request): Response
    {
        $currencyFrom = $this->currencyRepository->findOneBy([
            'code' => strtoupper($request->from),
        ]) ?? throw new NotFoundHttpException("Currency not found: $request->from");
        $currencyTo = $this->currencyRepository->findOneBy([
            'code' => strtoupper($request->to),
        ]) ?? throw new NotFoundHttpException("Currency not found: $request->to");

        try {
            $this->rateService->addRate($currencyFrom, $currencyTo);
        } catch (ServiceException $e) {
            throw new UnprocessableEntityHttpException('Невозможно получить курс');
        }


        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
