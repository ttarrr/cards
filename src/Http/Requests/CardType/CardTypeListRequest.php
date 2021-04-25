<?php

namespace App\Http\Requests\CardType;

use App\Util\DataTransferObject;
use Zakirullin\Mess\Mess;

/**
 * Class CardTypeListRequest
 * @package App\Http\Requests\CardType
 */
class CardTypeListRequest extends DataTransferObject
{
    /** @var int*/
    private $limit;

    /** @var int*/
    private $offset;

    /**
     * @param array $state
     * @return CardTypeListRequest
     */
    public static function fromState(array $state): CardTypeListRequest
    {
        $mess = new Mess($state);
        $dto = new static();

        $dto->limit = $mess['limit']->findAsInt() ?? 3;
        $dto->offset = $mess['offset']->findAsInt() ?? 0;

        return $dto;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return ($this->limit >= 0 && $this->limit <= 3) ? $this->limit : 3;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset >= 0 ? $this->offset : 0;
    }
}