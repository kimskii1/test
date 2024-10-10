<?php

declare(strict_types=1);

namespace App\Controller\Api\V1\Rate\Create;

use Symfony\Component\Validator\Constraints as Assert;

class CreateRateRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'Parameter "from" is required')]
        public string $from,
        #[Assert\NotBlank(message: 'Parameter "to" is required')]
        public string $to,
    ) {
    }
}
