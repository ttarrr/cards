<?php

namespace App\Http\Requests\Deck;

use App\Util\DataTransferObject;
use Zakirullin\Mess\Mess;

/**
 * Class DeckAddCardRequest
 * @package App\Http\Requests\Deck
 */
class DeckAddCardRequest extends DataTransferObject
{
    /** @var int */
    private $cardTypeId;

    /**
     * @param array $state
     * @return DeckAddCardRequest
     */
    public static function fromState(array $state): DeckAddCardRequest
    {
        $mess = new Mess($state);
        $dto = new static();

        $dto->cardTypeId = $mess['cardTypeId']->getAsInt();

        return $dto;
    }

    /**
     * @return int
     */
    public function getCardTypeId(): int
    {
        return $this->cardTypeId;
    }
}