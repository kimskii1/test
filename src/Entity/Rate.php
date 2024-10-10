<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Trait\EntityTimeTrait;
use App\Entity\Trait\EntityUuidTrait;
use App\Repository\RateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RateRepository::class)]
class Rate
{
    use EntityUuidTrait;
    use EntityTimeTrait;

    #[ORM\ManyToOne(targetEntity: Currency::class, inversedBy: 'ratesFrom')]
    private Currency $from;

    #[ORM\ManyToOne(targetEntity: Currency::class, inversedBy: 'ratesTo')]
    private Currency $to;

    #[ORM\Column(type: Types::FLOAT)]
    private float $value;

    public function getFrom(): Currency
    {
        return $this->from;
    }

    public function setFrom(Currency $from): self
    {
        $this->from = $from;
        return $this;
    }

    public function getTo(): Currency
    {
        return $this->to;
    }

    public function setTo(Currency $to): self
    {
        $this->to = $to;
        return $this;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;
        return $this;
    }
}
