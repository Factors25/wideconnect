<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\User\UserType;
use App\Service\Entity\User\UserService;
use App\Service\Generic\CrudGeneric;
use App\Service\Manager\ManagerService;
use App\Service\Router\RouterService;
use App\Service\Router\SingleParamRedirect;
use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;

class UserController extends CrudGeneric
{
    public function __construct(
        private readonly ManagerService  $managerService,
        private readonly UserService     $userService,
        private readonly RouterService   $routerService,
        private readonly RouterInterface $router
    )
    {
        parent::__construct($this->managerService, $this->router);
    }

    /**
     * @throws JsonException
     */
    #[Route('/user/{user}/api', name: 'user_api')]
    public function api(Request $request, User $user): JsonResponse
    {
        $this->service_object = $this->userService;
        $this->service_method = UserService::METHOD_API;
        $this->service_parameters = ['user' => $user];

        return $this->_api($request);
    }

    #[Route('/user/index', name: 'user_index')]
    public function index(Request $request): Response
    {
        $this->index_template = 'navigation/user/index.html.twig';

        return $this->_index($request);
    }

    #[Route('/user/{user}/show', name: 'user_show')]
    public function show(Request $request, User $user): Response
    {
        $this->index_template = 'navigation/user/show.html.twig';
        $this->index_template_parameters = ['user' => $user];

        return $this->_show($request);
    }

    /**
     * @throws JsonException
     */
    #[Route('/user/datatable', name: 'user_datatable')]
    public function datatable(Request $request): JsonResponse
    {
        $this->service_object = $this->userService;
        $this->service_method = UserService::METHOD_DATATABLE;

        return $this->_datatable($request);
    }

    #[Route('/user/create', name: 'user_create')]
    public function create(Request $request): JsonResponse
    {
        $this->form_template = 'components/user/_form.html.twig';
        $this->form_class = UserType::class;
        $this->form_object = new User();

        $this->pre_flush_service = $this->userService;
        $this->pre_flush_method = UserService::METHOD_PRE_CREATE;

        $this->success_redirect_url = new SingleParamRedirect('user_show', 'user');

        return $this->_create($request);
    }

    #[Route('/user/{user}/update', name: 'user_update')]
    public function update(Request $request, User $user): JsonResponse
    {
        $this->form_template = 'components/user/_form.html.twig';
        $this->form_class = UserType::class;
        $this->form_object = $user;

        $this->pre_flush_service = $this->userService;
        $this->pre_flush_method = UserService::METHOD_PRE_UPDATE;
        $this->pre_flush_parameters = ['oldPassword' => $this->form_object->getPassword()];

        if ($this->getUser() === $this->form_object) {
            [$_route, $_route_params] = $this->routerService->reverseUrl($request->headers->get('current-uri'));
            $this->success_redirect_url = $this->generateUrl($_route, $_route_params);
        }

        return $this->_update($request);
    }

    #[Route('/user/{user}/delete', name: 'user_delete')]
    public function delete(Request $request, User $user): JsonResponse
    {
        $this->form_template = 'components/user/_delete.html.twig';
        $this->form_object = $user;
        $this->form_template_parameters = ['user' => $this->form_object];

        return $this->_delete($request);
    }
}
