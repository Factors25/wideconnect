<?php

namespace App\Service\Generic;

use App\Service\Manager\ManagerService;
use App\Service\Router\SingleParamRedirect;
use App\Service\Validation\Error\Error;
use App\Service\Validation\Success\Success;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class CrudGeneric extends AbstractController
{
    // Index
    protected ?string $index_template = null;
    protected ?array $index_template_parameters = [];

    // Service
    protected mixed $service_object = null;
    protected ?string $service_method = null;
    protected ?array $service_parameters = [];

    // Form object
    protected ?FormInterface $form = null;
    protected ?string $form_template = null;
    protected ?array $form_template_parameters = [];
    protected ?string $form_class = null;
    protected mixed $form_object = null;
    protected ?array $form_options = [];

    // on Success
    protected ?array $success_parameters = [];
    protected mixed $success_redirect_url = null;

    // on Pre flush
    protected mixed $pre_flush_service = null;
    protected mixed $pre_flush_method = null;
    protected ?array $pre_flush_parameters = [];

    // on Post flush
    protected mixed $post_flush_service = null;
    protected ?string $post_flush_method = null;
    protected ?array $post_flush_parameters = [];

    // on Pre redirect
    protected mixed $pre_redirect;

    public function __construct(
        private readonly ManagerService  $managerService,
        private readonly RouterInterface $router
    )
    {
    }

    /**
     * @throws JsonException
     */
    protected function _api(Request $request): JsonResponse
    {
        if (
            $this->service_object &&
            method_exists($this->service_object, $this->service_method)
        ) {
            $method = $this->service_method;
            return (new JsonResponse)
                ->setJson($this->service_object->$method($request, $this->service_parameters));
        }

        throw $this->createNotFoundException('Api not found');
    }

    protected function _index(Request $request): Response
    {
        if (!$this->index_template) {
            throw $this->createNotFoundException('Index not found');
        }

        return $this->render($this->index_template, $this->index_template_parameters);
    }

    protected function _show(Request $request): Response
    {
        if (!$this->index_template) {
            throw $this->createNotFoundException('Show not found');
        }

        return $this->render($this->index_template, $this->index_template_parameters);
    }

    /**
     * @throws JsonException
     */
    protected function _datatable(Request $request): JsonResponse
    {
        if (
            $this->service_object &&
            method_exists($this->service_object, $this->service_method)
        ) {
            $method = $this->service_method;
            return (new JsonResponse)
                ->setJson($this->service_object->$method($request, $this->service_parameters));
        }

        throw $this->createNotFoundException('Datatable not found');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    protected function _create(Request $request): JsonResponse
    {
        if (!$this->form_class || !$this->form_object) {
            throw $this->createNotFoundException('Create not found');
        }

        $this->form = $this->createForm($this->form_class, $this->form_object, $this->form_options);
        $this->form->handleRequest($request);

        if ($this->form->isSubmitted()) {
            if ($this->form->isValid()) {
                $this->onPreFlush();

                $validation = $this->managerService->create($this->form_object);

                if ($validation['success']) {
                    $this->onPostFlush();
                    return new JsonResponse($this->getSuccess());
                }

                return new JsonResponse($this->getErrors($validation['errors']));
            }

            return new JsonResponse($this->getErrors());
        }

        return new JsonResponse($this->getErrors());
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    protected function _update(Request $request): JsonResponse
    {
        if (!$this->form_class || !$this->form_object) {
            throw $this->createNotFoundException('Update not found');
        }

        $this->form = $this->createForm($this->form_class, $this->form_object, $this->form_options);
        $this->form->handleRequest($request);

        if ($this->form->isSubmitted()) {
            if ($this->form->isValid()) {
                $this->onPreFlush();

                $validation = $this->managerService->update($this->form_object);

                if ($validation['success']) {
                    $this->onPostFlush();
                    return new JsonResponse($this->getSuccess());
                }

                return new JsonResponse($this->getErrors($validation['errors']));
            }

            return new JsonResponse($this->getErrors());
        }

        return new JsonResponse($this->getErrors());
    }

    protected function _delete(Request $request): JsonResponse
    {
        if ($request->getMethod() === Request::METHOD_GET) {
            return new JsonResponse($this->getErrors());
        }

        $csrf = $request->request->get('token');
        if ($this->isCsrfTokenValid('delete-resource', $csrf)) {
            $this->onPreFlush();

            $validation = $this->managerService->delete($this->form_object);

            if ($validation['success']) {
                $this->onPostFlush();
                return new JsonResponse($this->getSuccess());
            }

            return new JsonResponse($this->getErrors($validation['errors']));
        }

        return new JsonResponse($this->getErrors());
    }

    protected function onPreFlush(): void
    {
        if (
            $this->pre_flush_service &&
            method_exists($this->pre_flush_service, $this->pre_flush_method)
        ) {
            $method = $this->pre_flush_method;
            $this->pre_flush_service->$method($this->form, $this->form_object, $this->pre_flush_parameters);
        }
    }

    protected function onPostFlush(): void
    {
        if (
            $this->post_flush_service &&
            method_exists($this->post_flush_service, $this->post_flush_method)
        ) {
            $method = $this->post_flush_method;
            $this->post_flush_service->$method($this->form, $this->form_object, $this->post_flush_parameters);
        }
    }

    protected function getSuccess(): array
    {
        $data = [...$this->success_parameters];

        if (is_string($this->success_redirect_url)) {
            $data['redirect'] = $this->success_redirect_url;
        }

        if ($this->success_redirect_url instanceof SingleParamRedirect) {
            $data['redirect'] = $this->router->generate(
                $this->success_redirect_url->getRoute(),
                [$this->success_redirect_url->getParameter() => $this->form_object->getId()]
            );
        }

        return Success::generate($data);
    }

    protected function getErrors(array $errors = []): array
    {
        if ($this->form) {
            $errors = [...$errors];
        }

        return Error::generate(extraData: $this->getHtml($errors));
    }

    protected function getHtml(array $errors = []): array
    {
        $parameters = [];

        if ($this->form) {
            $parameters = ['form' => $this->form->createView()];
        }

        return [
            'html' => $this->renderView($this->form_template, [
                ...$parameters,
                'errors' => $errors,
                ...$this->form_template_parameters
            ])
        ];
    }
}