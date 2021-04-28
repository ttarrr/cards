<?php declare(strict_types = 1);

namespace App\Tests\Functional\Deck\Fixtures;

use Faker\Factory;

/**
 * Class UserIdFixture
 *
 * @package App\Tests\Functional\Deck\Fixtures
 */
class UserIdFixture
{
    /**
     * @return int
     */
    public function __invoke(): int
    {
        $faker = Factory::create();

        return $faker->numberBetween(1,99);
    }
}