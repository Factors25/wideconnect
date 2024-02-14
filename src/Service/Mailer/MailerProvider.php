<?php

namespace App\Service\Mailer;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class MailerProvider
{
    private ?Address $from = null;
    private ?Address $to = null;
    private ?string $subject = '';
    private ?string $template = '';
    private ?array $parameters = [];

    public function getFrom(): ?Address
    {
        $from = new Address($_ENV['MAILER_ADDRESS'], 'Hotspot ' . $_ENV['SOCIETY'] . ' - ne pas répondre');

        return $this->from ?: $from;
    }

    public function setFrom($from): self
    {
        $this->from = $from;
        return $this;
    }

    public function getTo(): ?Address
    {
        if (!$this->to) {
            return new Address($_ENV['MAILER_ADDRESS'], $_ENV['MAILER_NAME']);
        }

        return $this->to;
    }

    public function setTo(?string $email, ?string $name): self
    {
        $this->to = new Address($email, $name);
        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject($subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    public function getTemplate(): ?string
    {
        $template = 'extensions/mailer/mailer.html.twig';
        return $this->template ?: $template;
    }

    public function setTemplate($template): self
    {
        $this->template = $template;
        return $this;
    }

    public function getParameters(): ?array
    {
        return $this->parameters;
    }

    public function setParameters($parameters): self
    {
        $this->parameters = $parameters;
        return $this;
    }

    public function generate(): TemplatedEmail
    {
        return (new TemplatedEmail())
            ->from($this->getFrom())
            ->to($this->getTo())
            ->subject($this->getSubject())
            ->htmlTemplate($this->getTemplate())
            ->context($this->getParameters());
    }
}