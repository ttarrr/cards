<?php

namespace App\Tests\Functional\Deck\Actions;

use App\CardType\Entity\CardType;
use App\Deck\Entity\Deck;
use App\Deck\Entity\DeckEntry;
use App\Http\Requests\CardType\CardTypeCreateRequest;
use App\Http\Requests\Deck\DeckAddCardRequest;
use App\Http\Requests\Deck\DeckRemoveCardRequest;
use App\Tests\Functional\Deck\Fixtures\GeraltCardFixture;
use App\Tests\Functional\Deck\Fixtures\UserIdFixture;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class DeckRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     */
    public function testCreateDeck()
    {
        $this->clear();

        $userId = (new UserIdFixture())();
        $repository = $this->entityManager->getRepository(Deck::class);
        $generatedDeck = $repository->create($userId);
        $requestedDeck = $repository->findOneBy(['userId' => $userId]);

        $this->assertSame($generatedDeck, $requestedDeck);
    }

    public function testAddCardToDeck()
    {
        $this->clear();

        // Create Deck
        $userId = (new UserIdFixture())();
        $deckRepository = $this->entityManager->getRepository(Deck::class);
        /** @var Deck $deck */
        $deck = $deckRepository->create($userId);

        // Create CardType
        $cardDto = CardTypeCreateRequest::fromState((new GeraltCardFixture())());
        $cardRepository = $this->entityManager->getRepository(CardType::class);
        /** @var CardType $cardType */
        $cardType = $cardRepository->create($cardDto);

        // Add card to deck
        $addCardRequestDto = DeckAddCardRequest::fromState(['cardTypeId' => $cardType->getId()]);
        /** @var Deck $updatedDeck */
        $updatedDeck = $deckRepository->addCard($userId, $deck->getId(), $addCardRequestDto);

        // Make sure that some card is added
        $this->assertEquals(1, $updatedDeck->getNumberOfCards());

        // Make sure the correct type of card is added
        /** @var DeckEntry $cardEntry */
        $cardEntry = $updatedDeck->getDeckEntries()->first();
        $this->assertEquals($cardType->getId(), $cardEntry->getCardType()->getId());
    }

    public function testRemoveCardFromDeck()
    {
        $this->clear();

        // Create Deck
        $userId = (new UserIdFixture())();
        $deckRepository = $this->entityManager->getRepository(Deck::class);
        /** @var Deck $deck */
        $deck = $deckRepository->create($userId);

        // Create CardType
        $cardDto = CardTypeCreateRequest::fromState((new GeraltCardFixture())());
        $cardRepository = $this->entityManager->getRepository(CardType::class);
        /** @var CardType $cardType */
        $cardType = $cardRepository->create($cardDto);

        // Add card to deck
        $addCardRequestDto = DeckAddCardRequest::fromState(['cardTypeId' => $cardType->getId()]);
        /** @var Deck $updatedDeck */
        $updatedDeck = $deckRepository->addCard($userId, $deck->getId(), $addCardRequestDto);

        // Make sure that some card is added
        $this->assertEquals(1, $updatedDeck->getNumberOfCards());

        // Remove this card from deck
        /** @var DeckEntry $cardEntry */
        $cardEntry = $updatedDeck->getDeckEntries()->first();
        $removeCardRequestDto = DeckRemoveCardRequest::fromState(['deckEntryId' => (string)$cardEntry->getId()]);
        $deckRepository->removeCard($userId, $deck->getId(), $removeCardRequestDto);

        // Make sure card is removed
        $this->assertEquals(0, $updatedDeck->getNumberOfCards());
    }

    public function testAddThirdCardToDeck()
    {
        $this->expectException(\Exception::class);

        $this->clear();

        // Create Deck
        $userId = (new UserIdFixture())();
        $deckRepository = $this->entityManager->getRepository(Deck::class);
        /** @var Deck $deck */
        $deck = $deckRepository->create($userId);

        // Create CardType
        $cardDto = CardTypeCreateRequest::fromState((new GeraltCardFixture())());
        $cardRepository = $this->entityManager->getRepository(CardType::class);
        /** @var CardType $cardType */
        $cardType = $cardRepository->create($cardDto);

        // Add three cards of one type to deck
        $addCardRequestDto = DeckAddCardRequest::fromState(['cardTypeId' => $cardType->getId()]);
        /** @var Deck $updatedDeck */
        $deckRepository->addCard($userId, $deck->getId(), $addCardRequestDto);
        $deckRepository->addCard($userId, $deck->getId(), $addCardRequestDto);
        $deckRepository->addCard($userId, $deck->getId(), $addCardRequestDto);
    }

    public function testAddElevenCardsToDeck()
    {
        $this->expectException(\Exception::class);

        $this->clear();

        // Create Deck
        $userId = (new UserIdFixture())();
        $deckRepository = $this->entityManager->getRepository(Deck::class);
        /** @var Deck $deck */
        $deck = $deckRepository->create($userId);

        for ($i = 1; $i <= 11; $i++) {
            // Create CardType
            $cardDto = CardTypeCreateRequest::fromState([
                'title' => 'card_'.$i,
                'power' => $i
            ]);
            $cardRepository = $this->entityManager->getRepository(CardType::class);
            /** @var CardType $cardType */
            $cardType = $cardRepository->create($cardDto);

            // Add card to the deck
            $addCardRequestDto = DeckAddCardRequest::fromState(['cardTypeId' => $cardType->getId()]);
            /** @var Deck $updatedDeck */
            $deckRepository->addCard($userId, $deck->getId(), $addCardRequestDto);
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->clear();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }

    protected function clear() : void
    {
        $this->entityManager->createQuery(
            "DELETE FROM App\Deck\Entity\DeckEntry as d"
        )->execute();
        $this->entityManager->createQuery(
            "DELETE FROM App\Deck\Entity\Deck as d"
        )->execute();
        $this->entityManager->createQuery(
            "DELETE FROM App\CardType\Entity\CardType as c"
        )->execute();
    }
}