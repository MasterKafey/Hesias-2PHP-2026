<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\User\RegisterType;
use App\Security\TokenService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(): Response
    {
        return $this->render('Page/User/login.html.twig');
    }

    #[Route('/register', name: 'app_register')]
    public function register(
        EntityManagerInterface $entityManager,
        Request                $request, UserPasswordHasherInterface $hasher,
        MailerInterface        $mailer,
        TokenService           $tokenService
    ): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $hasher->hashPassword($user, $form->get('plainPassword')->getData());
            $user->setPassword($hash);
            $token = $tokenService->createToken($user);

            $entityManager->persist($user);
            $entityManager->persist($token);
            $entityManager->flush();

            $email = new Email()
                ->from('entrecote@gmail.com')
                ->to($user->getPseudo())
                ->html($this->renderView('Page/User/validate_account.html.twig', [
                    'token' => $token,
                ]));

            $mailer->send($email);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('Page/User/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/')]
    public function index(): Response
    {
        return $this->render('Page/index.html.twig');
    }

    #[Route('/validate/{value}', name: 'app_user_validate_account')]
    public function validateAccount(string $value, TokenService $tokenService, EntityManagerInterface $entityManager): Response
    {
        $token = $tokenService->getTokenByValue($value);

        if (null === $token) {
            throw $this->createNotFoundException('Token not found');
        }

        $token->getUser()->setEnabled(true);
        $entityManager->remove($token);
        $entityManager->flush();


        return $this->redirectToRoute('app_login');
    }

}
