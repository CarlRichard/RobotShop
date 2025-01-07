<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ORM\ManyToOne(targetEntity: DiscountCode::class)]

    private ?int $id = null;
    private ?DiscountCode $discountCode = null;

    /**
     * @var Collection<int, CartItem>
     */
    #[ORM\OneToMany(targetEntity: CartItem::class, mappedBy: 'cart')]
    private Collection $cartItems;

    public function __construct()
    {
        $this->cartItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, CartItem>
     */
    public function getCartItems(): Collection
    {
        return $this->cartItems;
    }

    public function addCartItem(CartItem $cartItem): static
    {
        if (!$this->cartItems->contains($cartItem)) {
            $this->cartItems->add($cartItem);
            $cartItem->setCart($this);
        }

        return $this;
    }

    public function removeCartItem(CartItem $cartItem): static
    {
        if ($this->cartItems->removeElement($cartItem)) {
            if ($cartItem->getCart() === $this) {
                $cartItem->setCart(null);
            }
        }

        return $this;
    }

    public function getDiscountCode(): ?DiscountCode
    {
        return $this->discountCode;
    }

    public function setDiscountCode(?DiscountCode $discountCode): static
    {
        $this->discountCode = $discountCode;
        return $this;
    }

    public function getTotalWithDiscount(): float
    {
        $total = 0;

        foreach ($this->cartItems as $cartItem) {
            $total += $cartItem->getRobot()->getPrice() * $cartItem->getQuantity();
        }

        if ($this->discountCode) {
            $discount = $this->discountCode->isPercentage()
                ? $total * ($this->discountCode->getDiscount() / 100)
                : $this->discountCode->getDiscount();

            if ($total >= $this->discountCode->getMinimumOrderAmount()) {
                $total -= $discount;
            }
        }

        return max($total, 0); // Pas de total négatif
    }
}
