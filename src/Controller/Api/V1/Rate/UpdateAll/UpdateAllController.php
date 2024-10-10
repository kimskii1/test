<?php

declare(strict_types=1);

namespace App\Controller\Api\V1\Rate\UpdateAll;

use App\Service\RateService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route(path: '/api/v1/rate/update', name: 'rate.update.all', methods: ['POST'])]
final readonly class UpdateAllController
{
    public function __construct(
        private RateService $rateService
    ) {
    }

    public function __invoke(): Response
    {
        $this->rateService->updateAllRates();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}