<?php

declare(strict_types=1);

namespace App\Controller\API;

use App\DTO\CrocodileUpdateDto;
use App\Entity\Crocodile;
use App\Form\Type\CrocodileType;
use App\Repository\CrocodileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class CrocodileApiController extends AbstractController
{
    #[Route('/crocodile', methods: 'GET')]
    public function read(CrocodileRepository $crocodileRepository): Response
    {
        $crocodiles = $crocodileRepository->findAll();

        return $this->json($crocodiles);
    }

    #[Route('/crocodile', methods: 'POST')]
    public function create(
        EntityManagerInterface $entityManager,
        #[MapRequestPayload]
        Crocodile              $crocodile
    ): Response
    {
        $entityManager->persist($crocodile);
        $entityManager->flush();

        return $this->json($crocodile);
    }

    #[Route('/crocodile/{id}', methods: 'PUT')]
    public function update(
        #[MapRequestPayload]
        CrocodileUpdateDto     $dto,
        Crocodile              $crocodile,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $crocodile
            ->setName($dto->name)
            ->setRace($dto->race)
            ->setAge($dto->age)
            ->setCommentQuIlEst($dto->commentQuIlEst);

        $entityManager->persist($crocodile);
        $entityManager->flush();

        return $this->json($crocodile);
    }

    #[Route('/crocodile/{id}', methods: 'DELETE')]
    public function delete(
        EntityManagerInterface $entityManager,
        Crocodile              $crocodile
    ): JsonResponse
    {
        $entityManager->remove($crocodile);
        $entityManager->flush();

        return $this->json(['message' => 'success']);
    }
}
