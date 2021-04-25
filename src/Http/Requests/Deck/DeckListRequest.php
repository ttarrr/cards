<?php

namespace App\Http\Requests\Deck;

use App\Util\DataTransferObject;
use Zakirullin\Mess\Mess;

/**
 * Class DeckListRequest
 * @package App\Http\Requests\Deck
 */
class DeckListRequest extends DataTransferObject
{
    /** @var int*/
    private $limit;

    /** @var int*/
    private $offset;

    /**
     * @param array $state
     * @return DeckListRequest
     */
    public static function fromState(array $state): DeckListRequest
    {
        $mess = new Mess($state);
        $dto = new static();

        $dto->limit = $mess['limit']->findAsInt() ?? 5;
        $dto->offset = $mess['offset']->findAsInt() ?? 0;

        return $dto;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return ($this->limit >= 0 && $this->limit <= 5) ? $this->limit : 5;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset >= 0 ? $this->offset : 0;
    }
}