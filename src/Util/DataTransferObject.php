<?php

declare(strict_types = 1);

namespace App\Util;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * Class DataTransferObject
 *
 * @package DataTransferObject
 */
abstract class DataTransferObject
{
    abstract public static function fromState(array $state);

    /**
     * @return array
     * @throws ExceptionInterface
     */
    public function toArray(): array
    {
        return (new Serializer([ new ObjectNormalizer() ], [ new JsonEncoder() ]))
            ->normalize($this, 'array');
    }
}
