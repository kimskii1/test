<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Trait\EntityTimeTrait;
use App\Entity\Trait\EntityUuidTrait;
use App\Repository\CurrencyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
class Currency
{
    use EntityUuidTrait;
    use EntityTimeTrait;

    #[ORM\Column(type: Types::STRING, length: 3, unique: true)]
    private string $code;

    #[ORM\OneToMany(targetEntity: Rate::class, mappedBy: 'from')]
    private Collection $ratesFrom;

    #[ORM\OneToMany(targetEntity: Rate::class, mappedBy: 'to')]
    private Collection $ratesTo;

    public function __construct()
    {
        $this->ratesFrom = new ArrayCollection();
        $this->ratesTo = new ArrayCollection();
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getRatesFrom(): Collection
    {
        return $this->ratesFrom;
    }

    public function addRatesFrom(Rate $rate): void
    {
        if (!$this->ratesFrom->contains($rate)) {
            $this->ratesFrom->add($rate);
            $rate->setFrom($this);
        }
    }

    public function getRatesTo(): Collection
    {
        return $this->ratesTo;
    }

    public function addRatesTo(Rate $rate): void
    {
        if (!$this->ratesTo->contains($rate)) {
            $this->ratesTo->add($rate);
            $rate->setTo($this);
        }
    }
}
