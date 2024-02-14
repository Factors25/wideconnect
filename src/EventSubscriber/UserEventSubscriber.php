<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class UserEventSubscriber implements EventSubscriberInterface
{
    private ?User $user;

    private Environment $twig;

    public function __construct(#[CurrentUser] ?User $user, Environment $twig)
    {
        $this->user = $user;
        $this->twig = $twig;
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) return;

        if(!$this->user) return;

        if(
            !in_array('ROLE_ADMIN', $this->user->getRoles(), true) ||
            !in_array('ROLE_SUPER_ADMIN', $this->user->getRoles(), true)
        ) {
            $template = $this->twig->render('security/hotspot/hotspot.html.twig');

            $event->setResponse(new Response($template, 503));
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