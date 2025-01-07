<?php

namespace App\Service;

use App\Entity\Robot;

class StockManager
{

    private array $stock = [];

    public function setStock(Robot $product, int $quantity): void
    {
        $this->stock[$product->getId()] = $quantity;
    }

    public function getStock(Robot $product): int
    {
        return $this->stock[$product->getId()] ?? 0;
    }


    public function canProcessOrder(Robot $product, int $quantity): bool
    {
        $currentStock = $this->getStock($product);
        return $currentStock >= $quantity;
    }

    public function processOrder(Robot $product, int $quantity): void
    {
        if (!$this->canProcessOrder($product, $quantity)) {
            throw new \RuntimeException('Insufficient stock');
        }
        $this->stock[$product->getId()] -= $quantity;
    }
}