<?php

namespace App\Twig;

use DateTimeInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\TwigTest;

class CustomTwigExtensions extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('bool', [$this, 'custom_boolean_filter']),
            new TwigFilter('created', [$this, 'custom_created_filter']),
            new TwigFilter('updated', [$this, 'custom_updated_filter']),
            new TwigFilter('deleted', [$this, 'custom_deleted_filter']),
            new TwigFilter('archived', [$this, 'custom_archived_filter']),
        ];
    }

    public function custom_boolean_filter($data): string
    {
        return $data ? 'Oui' : 'Non';
    }

    public function custom_created_filter(mixed $obj): string
    {
        $user = $obj->getCreatedBy() ? $obj->getCreatedBy()->getUsername() : null;
        if (is_null($user)) return '-';
        return 'Par ' . $user . ', ' . $this->custom_date_display($obj->getCreatedAt());
    }

    public function custom_updated_filter(mixed $obj): string
    {
        $user = $obj->getUpdatedBy() ? $obj->getUpdatedBy()->getUsername() : null;
        if (is_null($user)) return '-';
        return 'Par ' . $user . ', ' . $this->custom_date_display($obj->getUpdatedAt());
    }

    public function custom_deleted_filter(mixed $obj): string
    {
        $user = $obj->getDeletedBy() ? $obj->getDeletedBy()->getUsername() : null;
        if (is_null($user)) return '-';
        return 'Par ' . $user . ', ' . $this->custom_date_display($obj->getDeletedAt());
    }

    public function custom_archived_filter(mixed $obj): string
    {
        $user = $obj->getArchivedBy() ? $obj->getArchivedBy()->getUsername() : null;
        if (is_null($user)) return '-';
        return 'Par ' . $user . ', ' . $this->custom_date_display($obj->getArchivedAt());
    }

    public function getTests(): array
    {
        return [
            new TwigTest('instanceof', [$this, 'custom_instance_of'])
        ];
    }

    public function custom_instance_of($var, $instance): bool
    {
        return $var instanceof $instance;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('date', [$this, 'custom_date_display']),
        ];
    }

    public function custom_date_display(?DateTimeInterface $datetime, bool $time = true): string
    {
        if (is_null($datetime)) return '-';

        $data = 'le ' . $datetime->format('d/m/Y');

        if ($time) $data .= ' à ' . $datetime->format('H') . 'h' . $datetime->format('i');

        return $data;
    }
}