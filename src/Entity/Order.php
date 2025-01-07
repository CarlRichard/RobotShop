<?php

namespace App\Entity;


class Order
{
    private $items = [];

    public function getTotal(): float
    {
        return array_reduce($this->items, function($total, $item) {
            return $total + $item['price'] * $item['quantity'];
        }, 0);
    }

    public function addItem(string $name, float $price, int $quantity): void
    {
        $this->items[] = [
            'name' => $name, 
            'price' => $price, 
            'quantity' => $quantity];
    }
}