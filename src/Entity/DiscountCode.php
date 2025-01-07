<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class DiscountCode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $code = null;

    #[ORM\Column(type: 'float')]
    private ?float $discount = null; // Montant ou pourcentage de réduction

    #[ORM\Column(type: 'boolean')]
    private bool $isPercentage = false; // Indique si la réduction est un pourcentage

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $validFrom = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $validUntil = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $minimumOrderAmount = null; // Montant minimum pour appliquer le code

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(float $discount): self
    {
        $this->discount = $discount;
        return $this;
    }

    public function isPercentage(): bool
    {
        return $this->isPercentage;
    }

    public function setIsPercentage(bool $isPercentage): self
    {
        $this->isPercentage = $isPercentage;
        return $this;
    }

    public function getValidFrom(): ?\DateTimeInterface
    {
        return $this->validFrom;
    }

    public function setValidFrom(?\DateTimeInterface $validFrom): self
    {
        $this->validFrom = $validFrom;
        return $this;
    }

    public function getValidUntil(): ?\DateTimeInterface
    {
        return $this->validUntil;
    }

    public function setValidUntil(?\DateTimeInterface $validUntil): self
    {
        $this->validUntil = $validUntil;
        return $this;
    }

    public function getMinimumOrderAmount(): ?float
    {
        return $this->minimumOrderAmount;
    }

    public function setMinimumOrderAmount(?float $minimumOrderAmount): self
    {
        $this->minimumOrderAmount = $minimumOrderAmount;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }
}
