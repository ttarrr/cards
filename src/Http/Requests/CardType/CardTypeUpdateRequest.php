<?php

namespace App\Http\Requests\CardType;

use App\Util\DataTransferObject;
use Zakirullin\Mess\Mess;

/**
 * Class CardTypeUpdateRequest
 * @package App\Http\Requests\CardType
 */
class CardTypeUpdateRequest extends DataTransferObject
{
    /** @var string|null */
    private $title;

    /** @var string|null */
    private $power;

    /**
     * @param array $state
     * @return CardTypeUpdateRequest
     */
    public static function fromState(array $state): CardTypeUpdateRequest
    {
        $mess = new Mess($state);
        $dto = new static();

        $dto->title = $mess['title']->findAsString();
        $dto->power = $mess['power']->findAsInt();

        return $dto;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return int|null
     */
    public function getPower(): ?int
    {
        return $this->power;
    }
}