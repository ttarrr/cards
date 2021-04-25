<?php

namespace App\Http\Requests\CardType;

use App\Util\DataTransferObject;
use Zakirullin\Mess\Mess;

/**
 * Class CardTypeCreateRequest
 * @package App\Http\Requests\CardType
 */
class CardTypeCreateRequest extends DataTransferObject
{
    /** @var string*/
    private $title;

    /** @var int*/
    private $power;

    /**
     * @param array $state
     * @return CardTypeCreateRequest
     */
    public static function fromState(array $state): CardTypeCreateRequest
    {
        $mess = new Mess($state);
        $dto = new static();

        $dto->title = $mess['title']->getAsString();
        $dto->power = $mess['power']->getAsInt();

        return $dto;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getPower(): int
    {
        return $this->power;
    }
}