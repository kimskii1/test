<?php

declare(strict_types=1);

namespace App\Controller\Api\V1\Rate\Delete;

use Symfony\Component\Validator\Constraints as Assert;

class DeleteRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'Parameter "from" is required')]
        public string $from,
        #[Assert\NotBlank(message: 'Parameter "to" is required')]
        public string $to,
    ) {
    }
}
