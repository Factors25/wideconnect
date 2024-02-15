<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Service\Manager\ManagerService;
use DateTimeImmutable;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class UserEventSubscriber implements EventSubscriberInterface
{
    private ?TokenStorageInterface $tokenStorage;

    private Environment $twig;

    private ManagerService $managerService;

    public function __construct(TokenStorageInterface $tokenStorage, Environment $twig, ManagerService $managerService)
    {
        $this->tokenStorage = $tokenStorage;
        $this->twig = $twig;
        $this->managerService = $managerService;
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()?->getUser();

        if (!$event->isMainRequest()) {
            return;
        }

        if(!$user) {
            return;
        }

        $user->setLastActivityAt(new DateTimeImmutable());
        $this->managerService->update($user);

        if(
            !in_array('ROLE_ADMIN', $user->getRoles(), true) &&
            !in_array('ROLE_SUPER_ADMIN', $user->getRoles(), true)
        ) {
            $template = $this->twig->render('security/hotspot/hotspot.html.twig');

            $event->setResponse(new Response($template, 200));
            $event->stopPropagation();
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest'
        ];
    }
}