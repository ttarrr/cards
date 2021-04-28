<?php declare(strict_types = 1);

namespace App\Tests\Functional\CardType\Fixtures;

/**
 * Class GeraltCardFixture
 *
 * @package App\Tests\Functional\CardType\Fixtures
 */
class GeraltCardFixture
{
    /**
     * @return array<mixed>
     */
    public function __invoke(): array
    {
        return [
            'title' => 'Geralt',
            'power' => 10
        ];
    }
}