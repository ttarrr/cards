<?php declare(strict_types = 1);

namespace App\Tests\Functional\Deck\Fixtures;

/**
 * Class GeraltCardFixture
 *
 * @package App\Tests\Functional\Deck\Fixtures
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