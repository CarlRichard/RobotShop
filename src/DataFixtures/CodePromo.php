<?php

namespace App\DataFixtures;

use App\Entity\DiscountCode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CodePromo extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $discountCode1 = new DiscountCode();
        $discountCode1->setCode('PROMO10')
            ->setDiscount(10)
            ->setIsPercentage(true)
            ->setValidFrom(new \DateTime('2025-01-01'))
            ->setValidUntil(new \DateTime('2025-12-31'))
            ->setMinimumOrderAmount(50)
            ->setIsActive(true);

        $manager->persist($discountCode1);

        $discountCode2 = new DiscountCode();
        $discountCode2->setCode('FLAT5')
            ->setDiscount(5)
            ->setIsPercentage(false)
            ->setIsActive(true);

        $manager->persist($discountCode2);

        $manager->flush();

        $discountCode3 = new DiscountCode();
        $discountCode3->setCode('PROMO15')
            ->setDiscount(15)
            ->setIsPercentage(true)
            ->setValidFrom(new \DateTime('2025-01-01'))
            ->setValidUntil(new \DateTime('2025-12-31'))
            ->setMinimumOrderAmount(50)
            ->setIsActive(true);

        $manager->persist($discountCode3);

        $manager->flush();
    }
}
