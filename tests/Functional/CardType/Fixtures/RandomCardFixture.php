<?php declare(strict_types = 1);

namespace App\Tests\Functional\CardType\Fixtures;

use Faker\Factory;

/**
 * Class CardTypeFixture
 *
 * @package App\Tests\Functional\CardType\Fixtures
 */
class RandomCardFixture
{
    /**
     * @return array<mixed>
     */
    public function __invoke(): array
    {
        $faker = Factory::create();

        return [
            'title' => $faker->randomElement([
                'Daniam Of Kaveldun',
                'Zigriart Verdwerr',
                'Borzi',
                'Wigravar Ezzett',
                'Tubald Brezaut',
                'Tursald',
                'Tigovalt',
                'Tudagim Of Willowhain',
                'Wigraner Cezwaall',
                'Wir Of Cridam'
            ]),
            'power' => $faker->numberBetween(1,99)
        ];
    }
}