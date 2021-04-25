<?php

namespace App\DataFixtures;

use App\CardType\Entity\CardType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    // php bin/console doctrine:fixtures:load

    public function load(ObjectManager $manager)
    {
        $card_types_seed = [
            [
                'title' => 'Geralt',
                'power' => 10,
            ],
            [
                'title' => 'Ciri',
                'power' => 9
            ],
            [
                'title' => 'Vesemir',
                'power' => 5
            ],
            [
                'title' => 'Triss',
                'power' => 3
            ],
            [
                'title' => 'Aard sign',
                'power' => 0
            ],
        ];

        foreach ($card_types_seed as $card_seed) {
            $card = (new CardType())->setTitle($card_seed['title'])
                                    ->setPower($card_seed['power'])
                                    ->setImmortal(true);
            $manager->persist($card);
        }

        $manager->flush();
    }
}
