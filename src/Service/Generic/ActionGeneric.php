<?php

namespace App\Service\Generic;

use App\Service\Dom\DomService;
use Symfony\Component\Routing\RouterInterface;

class ActionGeneric
{
    protected mixed $object = null;
    protected ?string $object_class = '';
    protected ?string $url = '';
    protected ?string $title = '';
    protected ?string $message = '';
    protected ?array $parameters = [];
    protected ?array $extra = [];
    protected ?string $redirect = '';

    public function __construct(
        protected RouterInterface $router
    )
    {
    }

    public function _show(bool $content = true, string $role = 'show'): string
    {
        [$icon, $class] = match ($role) {
            'export-pdf' => ['fa-file-pdf', 'btn-primary'],
            default => ['fa-eye', 'btn-primary']
        };

        return implode('', [
            DomService::element(
                tag: 'a',
                attributes: [
                    'class' => 'btn btn-sm d-none d-lg-inline-flex ' . $class . (!$content ? ' btn-icon' : ''),
                    'href' => $this->url,
                    'title' => $this->title,
                    'target' => '_blank',
                    ...$this->extra
                ],
                content: DomService::icon($icon, $content ? '&nbsp; ' . $this->title : '')
            ),
            DomService::element(
                tag: 'a',
                attributes: [
                    'class' => 'btn btn-sm btn-icon d-inline-flex d-lg-none ' . $class,
                    'href' => $this->url,
                    'title' => $this->title,
                    'target' => '_blank',
                    ...$this->extra
                ],
                content: DomService::icon($icon)
            )
        ]);
    }

    public function _action(bool $content = true, string $role = 'create'): string
    {
        $this->getRedirect();

        [$icon, $class] = match ($role) {
            'update' => ['fa-edit', 'btn-secondary'],
            'delete' => ['fa-trash', 'btn-danger'],
            default => ['fa-circle-plus', 'btn-primary']
        };

        return implode('', [
            DomService::element(
                tag: 'button',
                attributes: [
                    'class' => 'btn btn-sm d-none d-lg-inline-flex ' . $class . (!$content ? ' btn-icon' : ''),
                    'data-modal' => $this->getClass(),
                    'data-role' => $role,
                    'data-title' => $this->title,
                    'data-url' => $this->url,
                    'data-message' => $this->message,
                    'title' => $this->title,
                    ...$this->extra
                ],
                content: DomService::icon($icon, $content ? '&nbsp; ' . $this->title : '')
            ),
            DomService::element(
                tag: 'button',
                attributes: [
                    'class' => 'btn btn-sm btn-icon d-inline-flex d-lg-none ' . $class,
                    'data-modal' => $this->getClass(),
                    'data-role' => $role,
                    'data-title' => $this->title,
                    'data-url' => $this->url,
                    'data-message' => $this->message,
                    'title' => $this->title,
                    ...$this->extra
                ],
                content: DomService::icon($icon)
            )
        ]);
    }

    public function _attributes(string $role = 'create'): string
    {
        $this->getRedirect();

        $data = [
            'class' => 'd-lg-inline-flex',
            'data-modal' => $this->getClass(),
            'data-role' => $role,
            'data-title' => $this->title,
            'data-url' => $this->url,
            'data-message' => $this->message,
            'title' => $this->title,
            ...$this->extra
        ];

        return implode('',
            array_map(static function ($key, $value) {
                return " $key=\"$value\"";
            }, array_keys($data), array_values($data))
        );
    }

    private function getClass(): ?string
    {
        $explode = explode('\\', $this->object_class);
        return array_pop($explode);
    }

    private function getRedirect(): void
    {
        $redirect = $this->parameters['redirect'] ?? null;
        if ($redirect) {
            $this->extra['data-redirect'] = $this->redirect;
        }
    }
}