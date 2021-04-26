<?php

namespace App\Deck\Repository;

use App\CardType\Entity\CardType;
use App\Deck\Entity\Deck;
use App\Deck\Entity\DeckEntry;
use App\Http\Requests\Deck\DeckAddCardRequest;
use App\Http\Requests\Deck\DeckRemoveCardRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class DeckRepository
 *
 * @package App\Deck;
 */
class DeckRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(EntityManagerInterface $em, ManagerRegistry $registry, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->validator = $validator;
        parent::__construct($registry, Deck::class);
    }

    /**
     * @param int $userId
     * @return Deck
     */
    public function create(int $userId) : Deck
    {
        $deck = (new Deck())->setUserId($userId);

        $this->em->persist($deck);
        $this->em->flush();

        return $deck;
    }

    /**
     * @param int $userId
     * @param string $deck_id
     * @param DeckAddCardRequest $dto
     * @return Deck
     * @throws \Exception
     */
    public function addCard(int $userId, string $deck_id, DeckAddCardRequest $dto) : Deck
    {
        /** @var Deck $deck */
        $deck = $this->findOneBy([
            'id' => $deck_id,
            'userId' => $userId
        ]);
        if (empty($deck)) {
            throw new \Exception('Deck not found', Response::HTTP_NOT_FOUND);
        }

        /** @var CardType $card */
        $card = $this->em->getRepository(CardType::class)->findOneBy(['id' => $dto->getCardTypeId()]);
        if (empty($card)) {
            throw new \Exception('Card not found', Response::HTTP_NOT_FOUND);
        }

        // Create deck entry
        $deckEntry = (new DeckEntry())->setCardType($card)
                                      ->setDeck($deck);
        $this->em->persist($deckEntry);
        $deck->addDeckEntry($deckEntry);

        // Validate deck
        $this->validate($deck);

        $this->em->persist($deck);
        $this->em->flush();

        return $deck;
    }

    /**
     * @param int $userId
     * @param string $deck_id
     * @param DeckRemoveCardRequest $dto
     * @return Deck
     * @throws \Exception
     */
    public function removeCard(int $userId, string $deck_id, DeckRemoveCardRequest $dto) : Deck
    {
        /** @var Deck $deck */
        $deck = $this->findOneBy([
            'id' => $deck_id,
            'userId' => $userId
        ]);
        if (empty($deck)) {
            throw new \Exception('Deck not found', Response::HTTP_NOT_FOUND);
        }

        /** @var DeckEntry $deckEntry */
        $deckEntry = $this->em->getRepository(DeckEntry::class)->findOneBy(['id' => $dto->getDeckEntryId()]);
        if (empty($deckEntry)) {
            throw new \Exception('DeckEntry not found', Response::HTTP_NOT_FOUND);
        }

        $deck->removeDeckEntry($deckEntry);

        $this->em->persist($deck);
        $this->em->flush();

        return $deck;
    }

    /**
     * @param Deck $deck
     * @return void
     * @throws \Exception
     */
    private function validate(Deck $deck)
    {
        $errors = $this->validator->validate($deck);

        if (count($errors) > 0) {
            throw new \Exception($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

}
