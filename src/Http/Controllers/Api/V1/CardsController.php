<?php

namespace App\Http\Controllers\Api\V1;

use App\CardType\Entity\CardType;
use App\Http\Requests\CardType\CardTypeCreateRequest;
use App\Http\Requests\CardType\CardTypeUpdateRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Http\Requests\CardType\CardTypeListRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/cards", name="cards_")
 */
class CardsController extends AbstractController
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
     * @Route("/", name="cards.list", methods={"GET"})
     */
    public function list(Request $request): Response
    {
        try {
            $requestDto = CardTypeListRequest::fromState($request->query->all());

            return  $this->json([
                'data' => $this->serializer->normalize($this->em->getRepository(CardType::class)->findBy(
                    [],
                    ['id' => 'ASC'],
                    $requestDto->getLimit(),
                    $requestDto->getOffset()
                )),
                'meta' => [
                    'limit' => $requestDto->getLimit(),
                    'offset' => $requestDto->getOffset(),
                    'total' => count($this->serializer->normalize($this->em->getRepository(CardType::class)->findBy(
                        []
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
     * @param Request $request
     * @return Response
     *
     * @Route("/", name="cards.create", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        try {
            $requestDto = CardTypeCreateRequest::fromState($request->request->all());

            return  $this->json([
                'data' => $this->serializer->normalize($this->em->getRepository(CardType::class)->create($requestDto))
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
     * @param int $card_type_id
     * @param Request $request
     * @return Response
     *
     * @Route("/{card_type_id}", name="cards.update", methods={"PATCH"}, requirements={"card_type_id"="\d+"})
     */
    public function update(int $card_type_id, Request $request): Response
    {
        try {
            $requestDto = CardTypeUpdateRequest::fromState($request->request->all());

            return  $this->json([
                'data' => $this->serializer->normalize($this->em->getRepository(CardType::class)->update($card_type_id, $requestDto))
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
     * @param int $card_type_id
     * @return Response
     *
     * @Route("/{card_type_id}", name="cards.delete", methods={"DELETE"}, requirements={"card_type_id"="\d+"})
     */
    public function delete(int $card_type_id): Response
    {
        try {
            $this->em->getRepository(CardType::class)->delete($card_type_id);

            return $this->json([
                'success' => true
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