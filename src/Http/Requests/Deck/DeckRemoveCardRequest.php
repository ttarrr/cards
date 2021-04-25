<?php

namespace App\Http\Requests\Deck;

use App\Util\DataTransferObject;
use Zakirullin\Mess\Mess;

/**
 * Class DeckRemoveCardRequest
 * @package App\Http\Requests\Deck
 */
class DeckRemoveCardRequest extends DataTransferObject
{
    /** @var string */
    private $deckEntryId;

    /**
     * @param array $state
     * @return DeckRemoveCardRequest
     */
    public static function fromState(array $state): DeckRemoveCardRequest
    {
        $mess = new Mess($state);
        $dto = new static();

        $dto->deckEntryId = $mess['deckEntryId']->getAsString();

        return $dto;
    }

    /**
     * @return string
     */
    public function getDeckEntryId(): string
    {
        return $this->deckEntryId;
    }
}