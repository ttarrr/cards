<?php

namespace App\Http\Validations\CardTypeLimit;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CardTypeLimit extends Constraint
{
    public const MORE_THAN_TWO_CARDS = 'ce0e4d93-4e79-4a48-865c-06dc18933f42';

    protected static $errorNames = [
        self::MORE_THAN_TWO_CARDS => 'MORE_THAN_TWO_CARDS',
    ];

    public $message = 'You can only hold two cards of the same type in a deck.';
}