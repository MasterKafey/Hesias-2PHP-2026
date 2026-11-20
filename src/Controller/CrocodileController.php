<?php

namespace App\Controller;

use App\Entity\Crocodile;
use App\Form\Type\CrocodileType;
use App\Service\MathService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/crocodile')]
class CrocodileController extends AbstractController
{
    public function __construct(
        EntityManagerInterface $entityManager,
    )
    {

    }

    #[Route('/create', 'app_crocodile_create')]
    public function create(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {
        $crocodile = new Crocodile();
        $form = $this->createForm(CrocodileType::class, $crocodile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($crocodile);
            $entityManager->flush();

            return $this->redirectToRoute('app_crocodile_read');
        }

        return $this->render('Page/Crocodile/create.html.twig', [
            'my_form' => $form->createView(),
        ]);
    }

    #[Route('/', 'app_crocodile_read')]
    public function read(EntityManagerInterface $entityManager): Response
    {
        $crocodiles = $entityManager->getRepository(Crocodile::class)->findAll();

        return $this->render('Page/Crocodile/list.html.twig', [
            'crocodiles' => $crocodiles,
        ]);
    }

    #[Route('/update/{id}')]
    public function update(Crocodile $crocodile, EntityManagerInterface $entityManager): Response
    {
        $crocodile->setAge($crocodile->getAge() + 1);
        $entityManager->flush();
        return $this->redirectToRoute('app_crocodile_read');
    }

    #[Route('/delete/{id}')]
    public function delete(Crocodile $crocodile, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($crocodile);
        $entityManager->flush();

        return $this->redirectToRoute('app_crocodile_read');
    }
}
