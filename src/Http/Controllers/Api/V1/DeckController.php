<?php

namespace App\Http\Controllers\Api\V1;

use App\Deck\Entity\Deck;
use App\Deck\Entity\DeckEntry;
use App\Http\Requests\Deck\DeckAddCardRequest;
use App\Http\Requests\Deck\DeckListRequest;
use App\Http\Requests\Deck\DeckRemoveCardRequest;
use App\Security\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/deck", name="decks_")
 */
class DeckController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->serializer = $serializer;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/", name="deck.list", methods={"GET"})
     */
    public function list(Request $request): Response
    {
        try {
            $requestDto = DeckListRequest::fromState($request->query->all());

            /** @var User $user */
            $user = $this->getUser();

            return  $this->json([
                'data' => $this->serializer->normalize($this->em->getRepository(Deck::class)->findBy(
                    ['userId' => $user->getId()],
                    ['createdAt' => 'DESC'],
                    $requestDto->getLimit(),
                    $requestDto->getOffset()
                )),
                'meta' => [
                    'limit' => $requestDto->getLimit(),
                    'offset' => $requestDto->getOffset(),
                    'total' => count($this->serializer->normalize($this->em->getRepository(Deck::class)->findBy(
                        ['userId' => $user->getId()]
                    )))
                ]
            ]);

        } catch (\Throwable $e) {
            $statusCode = !empty($e->getCode()) ? $e->getCode() : Response::HTTP_UNPROCESSABLE_ENTITY;
            return $this->json([
                'error' => Response::$statusTexts[$statusCode],
                'message' => $e->getMessage()
            ], $statusCode);
        }
    }

    /**
     * @return Response
     *
     * @Route("/", name="deck.create", methods={"POST"})
     */
    public function create(): Response
    {
        try {
            /** @var User $user */
            $user = $this->getUser();

            return  $this->json([
                'data' => $this->serializer->normalize($this->em->getRepository(Deck::class)->create($user->getId()))
            ]);

        } catch (\Throwable $e) {
            $statusCode = !empty($e->getCode()) ? $e->getCode() : Response::HTTP_UNPROCESSABLE_ENTITY;
            return $this->json([
                'error' => Response::$statusTexts[$statusCode],
                'message' => $e->getMessage()
            ], $statusCode);
        }
    }

    /**
     * @param string $deck_id
     * @return Response
     *
     * @Route("/{deck_id}", name="deck.details", methods={"GET"})
     */
    public function deckDetails(string $deck_id): Response
    {
        try {
            /** @var User $user */
            $user = $this->getUser();

            return  $this->json([
                'data' => $this->serializer->normalize($this->em->getRepository(Deck::class)->findOneBy(
                    [
                        'id' => $deck_id,
                        'userId' => $user->getId()
                    ]
                ))
            ]);

        } catch (\Throwable $e) {
            $statusCode = !empty($e->getCode()) ? $e->getCode() : Response::HTTP_UNPROCESSABLE_ENTITY;
            return $this->json([
                'error' => Response::$statusTexts[$statusCode],
                'message' => $e->getMessage()
            ], $statusCode);
        }
    }

    /**
     * @param string $deck_id
     * @param Request $request
     * @return Response
     *
     * @Route("/{deck_id}/card", name="deck.add_entry", methods={"POST"})
     */
    public function addCard(string $deck_id, Request $request): Response
    {
        try {
            $requestDto = DeckAddCardRequest::fromState($request->request->all());

            /** @var User $user */
            $user = $this->getUser();

            return  $this->json([
                'data' => $this->serializer->normalize($this->em->getRepository(Deck::class)->addCard($user->getId(), $deck_id, $requestDto))
            ]);

        } catch (\Throwable $e) {
            $statusCode = !empty($e->getCode()) ? $e->getCode() : Response::HTTP_UNPROCESSABLE_ENTITY;
            return $this->json([
                'error' => Response::$statusTexts[$statusCode],
                'message' => $e->getMessage()
            ], $statusCode);
        }
    }

    /**
     * @param string $deck_id
     * @param Request $request
     * @return Response
     *
     * @Route("/{deck_id}/card", name="deck.remove_entry", methods={"DELETE"})
     */
    public function removeCard(string $deck_id, Request $request): Response
    {
        try {
            $requestDto = DeckRemoveCardRequest::fromState($request->request->all());

            /** @var User $user */
            $user = $this->getUser();

            return  $this->json([
                'data' => $this->serializer->normalize($this->em->getRepository(Deck::class)->removeCard($user->getId(), $deck_id, $requestDto))
            ]);

        } catch (\Throwable $e) {
            $statusCode = !empty($e->getCode()) ? $e->getCode() : Response::HTTP_UNPROCESSABLE_ENTITY;
            return $this->json([
                'error' => Response::$statusTexts[$statusCode],
                'message' => $e->getMessage()
            ], $statusCode);
        }
    }
}