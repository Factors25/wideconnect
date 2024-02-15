<?php

namespace App\Service\Entity\User;

use App\Entity\User;
use App\Service\Generic\ActionGeneric;
use Symfony\Component\Routing\RouterInterface;

class UserAction extends ActionGeneric
{
    public function __construct(RouterInterface $router)
    {
        parent::__construct($router);
        $this->redirect = $this->indexUrl();
    }

    public function indexUrl(array $parameters = []): string
    {
        return $this->router->generate('user_index', $parameters);
    }

    public function showUrl(User $item, array $parameters = []): string
    {
        return $this->router->generate('user_show', [
            'user' => $item->getId(), ...$parameters
        ]);
    }

    public function createUrl(array $parameters = []): string
    {
        return $this->router->generate('user_create', $parameters);
    }

    public function updateUrl(User $item, array $parameters = []): string
    {
        return $this->router->generate('user_update', [
            'user' => $item->getId(), ...$parameters
        ]);
    }

    public function deleteUrl(User $item, array $parameters = []): string
    {
        return $this->router->generate('user_delete', [
            'user' => $item->getId(), ...$parameters
        ]);
    }

    public function showButton(User $item, bool $content = false, array $parameters = []): string
    {
        $this->object = $item;
        $this->url = $this->showUrl($item, $parameters);
        $this->title = UserConstant::SHOW_MESSAGE;

        return $this->_show($content);
    }

    public function createButton(bool $content = true, array $parameters = []): string
    {
        $this->object_class = User::class;
        $this->url = $this->createUrl($parameters);
        $this->title = UserConstant::CREATE_MESSAGE;
        $this->message = UserConstant::CREATE_MESSAGE_SUCCESS;

        return $this->_action($content);
    }

    public function updateButton(User $item, bool $content = true, array $parameters = []): string
    {
        $this->object_class = User::class;
        $this->url = $this->updateUrl($item, $parameters);
        $this->title = UserConstant::UPDATE_MESSAGE . ' ' . $item->getUsername();
        $this->message = UserConstant::UPDATE_MESSAGE_SUCCESS;

        return $this->_action($content, 'update');
    }

    public function deleteButton(User $item, bool $content = true, array $parameters = []): string
    {
        $this->object_class = User::class;
        $this->url = $this->deleteUrl($item, $parameters);
        $this->title = UserConstant::DELETE_MESSAGE . ' ' . $item->getUsername();
        $this->message = UserConstant::DELETE_MESSAGE_SUCCESS;
        if (array_key_exists('redirect', $parameters)) {
            $this->extra['data-redirect'] = $this->redirect;
        }

        return $this->_action($content, 'delete');
    }

    public function attributes(array $parameters = []): string
    {
        $this->object_class = User::class;
        $this->url = $this->createUrl($parameters);
        $this->title = UserConstant::CREATE_MESSAGE;
        $this->message = UserConstant::CREATE_MESSAGE_SUCCESS;

        return $this->_attributes();
    }
}