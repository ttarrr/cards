<?php

namespace App\CardType\Repository;

use App\CardType\Entity\CardType;
use App\Http\Requests\CardType\CardTypeCreateRequest;
use App\Http\Requests\CardType\CardTypeUpdateRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class CardTypeRepository
 *
 * @package App\CardType;
 */
class CardTypeRepository extends ServiceEntityRepository
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
        parent::__construct($registry, CardType::class);
    }

    /**
     * @param CardTypeCreateRequest $dto
     * @return CardType
     * @throws \Exception
     */
    public function create(CardTypeCreateRequest $dto) : CardType
    {
        try {
            $card = (new CardType())
                ->setTitle($dto->getTitle())
                ->setPower($dto->getPower())
                ->setImmortal(false);

            $this->validate($card);

            $this->em->persist($card);
            $this->em->flush();

            return $card;
        } catch (UniqueConstraintViolationException $e) {
            throw new \Exception('A card with the same name already exists', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param int $card_type_id
     * @param CardTypeUpdateRequest $dto
     * @return CardType
     * @throws \Exception
     */
    public function update(int $card_type_id, CardTypeUpdateRequest $dto) : CardType
    {
        try {
            /** @var CardType $card */
            $card = $this->findOneBy(['id' => $card_type_id]);

            if (empty($card)) {
                throw new \Exception('Card not found', Response::HTTP_NOT_FOUND);
            }

            $card
                ->setTitle($dto->getTitle() ?? $card->getTitle())
                ->setPower($dto->getPower() ?? $card->getPower());

            $this->validate($card);

            $this->em->persist($card);
            $this->em->flush();

            return $card;
        } catch (UniqueConstraintViolationException $e) {
            throw new \Exception('A card with the same name already exists', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param int $card_type_id
     * @return void
     * @throws \Exception
     */
    public function delete(int $card_type_id) : void
    {
        try {
            /** @var CardType $card */
            $card = $this->findOneBy(['id' => $card_type_id]);

            if (empty($card)) {
                throw new \Exception('Card not found', Response::HTTP_NOT_FOUND);
            }

            if ($card->getImmortal() === true) {
                throw new \Exception("You can't kill an immortal card, you silly", Response::HTTP_I_AM_A_TEAPOT);
            }

            $this->em->remove($card);
            $this->em->flush();

            return;
        } catch (UniqueConstraintViolationException $e) {
            throw new \Exception('A card with the same name already exists', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param CardType $card
     * @return void
     * @throws \Exception
     */
    private function validate(CardType $card)
    {
        $errors = $this->validator->validate($card);

        if (count($errors) > 0) {
            throw new \Exception($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    // /**
    //  * @return Card[] Returns an array of Card objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Card
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
