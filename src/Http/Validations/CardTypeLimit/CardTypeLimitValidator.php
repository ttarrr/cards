<?php

namespace App\Http\Validations\CardTypeLimit;

use App\Deck\Entity\DeckEntry;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Unique;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class CardTypeLimitValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof CardTypeLimit) {
            throw new UnexpectedTypeException($constraint, CardTypeLimit::class);
        }

        if (null === $value) {
            return;
        }

        if (!$value instanceof Collection) {
            throw new UnexpectedValueException($value, 'Collection');
        }

        $cardEntries = [];

        /** @var DeckEntry $deckEntry */
        foreach ($value as $deckEntry) {
            $cardEntries[] = $deckEntry->getCardType()->getTitle();
        }

        foreach (array_count_values($cardEntries) as $key => $val) {
            if ($val > 2) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $this->formatValue($value))
                    ->setCode(CardTypeLimit::MORE_THAN_TWO_CARDS)
                    ->addViolation();
            }
        }
    }
}