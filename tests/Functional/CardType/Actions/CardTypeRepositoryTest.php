<?php

namespace App\Tests\Functional\CardType\Actions;

use App\CardType\Entity\CardType;
use App\Http\Requests\CardType\CardTypeCreateRequest;
use App\Http\Requests\CardType\CardTypeUpdateRequest;
use App\Tests\Functional\CardType\Fixtures\GeraltCardFixture;
use App\Tests\Functional\CardType\Fixtures\RandomCardFixture;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class CardTypeRepositoryTest extends KernelTestCase
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
     * @throws ExceptionInterface
     */
    public function testCreateCardType()
    {
        $this->clear();

        $dto = CardTypeCreateRequest::fromState((new GeraltCardFixture())());
        $repository = $this->entityManager->getRepository(CardType::class);
        $generatedCard = $repository->create($dto);
        $requestedCard = $repository->findOneBy($dto->toArray());

        $this->assertSame($generatedCard, $requestedCard);
    }

    /**
     * @throws ExceptionInterface
     */
    public function testCreatingDuplicatedCardType()
    {
        $this->expectException(\Exception::class);

        $this->clear();

        $dto = CardTypeCreateRequest::fromState((new GeraltCardFixture())());
        $repository = $this->entityManager->getRepository(CardType::class);
        $repository->create($dto);
        $repository->create($dto);
    }

    /**
     */
    public function testUpdateCardType()
    {
        $this->clear();

        // create Geralt card
        $dto = CardTypeCreateRequest::fromState((new GeraltCardFixture())());
        $repository = $this->entityManager->getRepository(CardType::class);
        /** @var CardType $generatedCard */
        $generatedCard = $repository->create($dto);

        // update Geralt card with random card data
        $randCardDto = CardTypeUpdateRequest::fromState((new RandomCardFixture())());
        $updatedCard = $repository->update($generatedCard->getId(), $randCardDto);

        // Request card with initial ID
        $requestedCard = $repository->findOneBy([ 'id' => $generatedCard->getId() ]);

        $this->assertSame($requestedCard, $updatedCard);
    }

    /**
     */
    public function testUpdateWithExistingCardType()
    {
        $this->expectException(\Exception::class);

        $this->clear();

        // create Geralt card
        $dto = CardTypeCreateRequest::fromState((new GeraltCardFixture())());
        $repository = $this->entityManager->getRepository(CardType::class);
        /** @var CardType $generatedCard */
        $generatedCard = $repository->create($dto);

        // create Random card
        $randCardDto = CardTypeCreateRequest::fromState((new RandomCardFixture())());
        $repository = $this->entityManager->getRepository(CardType::class);
        /** @var CardType $randCard */
        $randCard = $repository->create($randCardDto);

        // update Random card with Geralt card data
        $geraltUpdateDto = CardTypeUpdateRequest::fromState((new GeraltCardFixture())());
        $repository->update($randCard->getId(), $geraltUpdateDto);
    }

    /**
     */
    public function testDeleteCard()
    {
        $this->clear();

        // Create card
        $dto = CardTypeCreateRequest::fromState((new GeraltCardFixture())());
        $repository = $this->entityManager->getRepository(CardType::class);
        /** @var CardType $generatedCard */
        $generatedCard = $repository->create($dto);

        // Delete card
        $generatedCardId = $generatedCard->getId();
        $repository->delete($generatedCardId);

        $this->assertNull($repository->findOneBy([ 'id' => $generatedCardId ]));
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