<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2014 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\Action;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

/**
 * The default action for create and update pages
 */
abstract class FormAction extends Action
{
    /**
     * Template engine
     *
     * @var EngineInterface $templating
     */
    protected $templating;

    /**
     * Form factory
     *
     * @var FormFactoryInterface $formFactory
     */
    protected $formFactory;

    /**
     * Router
     *
     * @var RouterInterface $router
     */
    protected $router;

    /**
     * Indject dependencies
     *
     * @param EngineInterface $templating
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface $router
     */
    public function __construct(EngineInterface $templating, FormFactoryInterface $formFactory, RouterInterface $router)
    {
        $this->templating  = $templating;
        $this->formFactory = $formFactory;
        $this->router      = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse(Request $request)
    {
        $model = $this->getModel($request);
        $form  = $this->createForm($model);

        if ($this->handleForm($request, $form)) {
            $this->onFormValid($form);

            return $this->createSuccessResponse($form);
        }

        return $this->createResponse($form);
    }

    /**
     * Get model
     *
     * @param Request $request
     *
     * @return mixed
     */
    abstract protected function getModel(Request $request);

    /**
     * Create form
     *
     * @param mixed $model
     *
     * @return Form
     */
    protected function createForm($model)
    {
        return $this->formFactory
            ->create($this->getFormType($this->parameters['form_type']), $model)
            ->add('submit', 'submit');
    }

    /**
     * Handle form
     *
     * @param Request $request
     * @param Form $form
     *
     * @return Response|null
     */
    protected function handleForm(Request $request, Form $form)
    {
        return $form->handleRequest($request)->isSubmitted() && $form->isValid();
    }

    /**
     * Persist model from form
     *
     * @param Form $form
     */
    protected function onFormValid(Form $form)
    {
        $this->modelManager->persist($form->getData());
    }

    /**
     * Create success response
     *
     * @return Response
     */
    protected function createSuccessResponse(Form $form)
    {
        return new RedirectResponse(
            $this->router->generate(
                $this->parameters['redirect']['name'],
                $this->parameters['redirect']['parameters']
            )
        );
    }

    /**
     * Create response
     *
     * @param Form $form
     * @param array $parameters
     *
     * @return Response
     */
    protected function createResponse($form, array $parameters = [])
    {
        return new Response(
            $this->templating->render(
                $this->parameters['view'],
                array_merge(
                    ['form' => $form->createView()],
                    $parameters
                )
            )
        );
    }

    /**
     * Get form type
     *
     * @param string $formType
     *
     * @return string|Symfony\Component\Form\AbstractType
     */
    protected function getFormType($formType)
    {
        return class_exists($formType) ? new $formType : $formType;
    }
}
