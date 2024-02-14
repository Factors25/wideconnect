<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\Security\RegisterType;
use App\Repository\UserRepository;
use App\Service\Mailer\MailerProvider;
use App\Service\Mailer\MailerService;
use App\Service\Manager\ManagerService;
use App\Service\Security\SecurityService;
use App\Service\Validation\Error\UnknownError;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class RegisterController extends AbstractController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly TokenGeneratorInterface     $tokenGenerator,
        private readonly ManagerService              $managerService,
        private readonly MailerService               $mailerService,
        private readonly SecurityService             $securityService,
        private readonly UserRepository              $userRepository
    )
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/register', name: 'register')]
    public function register(Request $request): Response
    {
        if ($this->getUser()) return $this->redirectToRoute('home');

        $user = new User();

        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        $errors = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $user
                ->setPassword(
                    $this->userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                )
                ->setToken([
                    'expires' => time() + 3600,
                    'signature' => $this->tokenGenerator->generateToken()
                ]);

            $validator = $this->managerService->create($user);
            if ($validator['success'] === false) {
                $errors[] = $validator['errors'];
                return $this->render('security/register/register.html.twig', [
                    'form' => $form->createView(),
                    'errors' => $errors
                ]);
            }

            $mailerProvider = (new MailerProvider())
                ->setSubject('Contact support - inscription')
                ->setParameters([
                    'template' => $this->renderView('security/register/register_mailer.html.twig', [
                        'button_url' => $this->securityService->generateVerifyUserUrl('register_verify', $user)
                    ])
                ]);

            $validator = $this->mailerService->send($mailerProvider);
            if ($validator['success'] === false) {
                $errors[] = $validator['errors'];
                return $this->render('security/register/register.html.twig', [
                    'form' => $form->createView(),
                    'errors' => $errors
                ]);
            }

            $this->addFlash('info', 'Un email vous a été envoyé. Veuillez regarder votre boite mail.');

            return $this->redirectToRoute('login');
        }

        return $this->render('security/register/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/register/verify', name: 'register_verify')]
    public function verify(Request $request): Response
    {
        if ($this->getUser()) return $this->redirectToRoute('home');

        $email = $request->get('email');
        $token = $request->get('token');

        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            $this->addFlash('error', 'L\'utilisateur n\'existe pas.');
            return $this->redirectToRoute('login');
        }

        if ($token !== $user->getToken()['signature']) {
            $this->addFlash('error', 'Le lien est incorrect.');
            return $this->redirectToRoute('login');
        }

        if (time() > $user->getToken()['expires']) {
            $this->addFlash('error', 'Le lien a expiré.');
            return $this->redirectToRoute('login');
        }

        if ($user->isVerify() === true) {
            $this->addFlash('warning', 'L\'utilisateur est déjà actif.');
            return $this->redirectToRoute('login');
        }

        $user->setVerify(true);
        $user->setActive(true);
        $user->setToken([
            'expires' => time(),
            'signature' => $this->tokenGenerator->generateToken()
        ]);

        $validator = $this->managerService->update($user);
        if ($validator['success'] === false) {
            $this->addFlash('error', UnknownError::DEFAULT_MESSAGE);
            return $this->redirectToRoute('login');
        }

        $this->addFlash('success', 'Compte activé avec succès, vous pouvez vous connecter !');

        return $this->redirectToRoute('login');
    }
}