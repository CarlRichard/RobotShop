<?php
namespace App\Service;

use App\Entity\Order;

class Discount
{
    public function calculateDiscountedTotal(Order $order, string $discountCode): float
    {
        $total = $order->getTotal();
        $discount = 0;

        switch ($discountCode) {
            case 'SUMMER10':
                if ($total < 50) {
                    throw new \Exception('Montant de la commande inférieur au minimum requis pour ce code de réduction');
                }
                $discount = $total * 0.1;
                break;
            case 'WELCOME5':
                $discount = 5;
                break;
            default:
                throw new \Exception('Code de réduction invalide');
        }
        return $total - $discount;
    }
}