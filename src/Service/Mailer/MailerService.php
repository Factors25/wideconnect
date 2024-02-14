<?php

namespace App\Service\Mailer;

use App\Service\Environment\Env;
use App\Service\Validation\Error\Error;
use App\Service\Validation\Error\UnknownError;
use App\Service\Validation\Success\Success;
use Exception;
use Symfony\Bridge\Twig\Mime\BodyRenderer;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Mailer\EventListener\MessageListener;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Twig\Environment;

readonly class MailerService
{
    public function __construct(
        private Environment $environment
    )
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function send(MailerProvider $mailerProvider): array
    {
        try {
            $messageListener = new MessageListener(null, new BodyRenderer($this->environment));

            $eventDispatcher = new EventDispatcher();
            $eventDispatcher->addSubscriber($messageListener);

            $transport = Transport::fromDsn($_ENV['MAILER_DSN'], $eventDispatcher);
            $mailer = new Mailer($transport, null, $eventDispatcher);

            $mailer->send($mailerProvider->generate());
        } catch (Exception $exception) {
            return Error::generate(Env::isDev() ? $exception->getMessage() : UnknownError::DEFAULT_MESSAGE);
        }

        return Success::generate();
    }
}