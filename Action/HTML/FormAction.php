<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2014 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\Action\HTML;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Elao\Bundle\AdminBundle\Action\Action;
use Elao\Bundle\AdminBundle\Behaviour\NotifierInterface;

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
     * Notifier
     *
     * @var NotifierInterface $notifier
     */
    protected $notifier;

    /**
     * Indject dependencies
     *
     * @param EngineInterface $templating
     * @param FormFactoryInterface $formFactory
     * @param NotifierInterface $notifier
     */
    public function __construct(EngineInterface $templating, FormFactoryInterface $formFactory, NotifierInterface $notifier)
    {
        $this->templating  = $templating;
        $this->formFactory = $formFactory;
        $this->notifier    = $notifier;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse(Request $request)
    {
        $model = $this->getModel($request);
        $form  = $this->createForm($model);

        if ($this->handleForm($request, $form)) {
            if ($form->isValid()) {
                $this->onFormValid($form);

                return $this->createSuccessResponse($request, $form);
            } else {
                $this->onFormInvalid($form);
            }
        }

        return $this->createResponse($this->getViewParameters($request, $form));
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
        return $form->handleRequest($request)->isSubmitted();
    }

    /**
     * On form valid
     *
     * @param Form $form
     */
    protected function onFormValid(Form $form)
    {
        $this->modelManager->persist($form->getData());

        $this->notifier->notifySuccess($this->getNotifyMessage($form, 'success'));
    }

    /**
     * On form invalid
     *
     * @param Form $form
     */
    protected function onFormInvalid(Form $form)
    {
        $this->notifier->notifyError($this->getNotifyMessage($form, 'error'));
    }

    /**
     * Create success response
     *
     * @param Request $request
     * @param Form $form
     *
     * @return Response
     */
    protected function createSuccessResponse(Request $request, Form $form)
    {
        return new RedirectResponse($this->getSuccessUrl($request, $form->getData()));
    }

    /**
     * Get success url for given model
     *
     * @param mixed $data
     *
     * @return string
     */
    protected function getSuccessUrl(Request $request, $data)
    {
        return $this->routeResolver->getUrl($this->parameters['redirection'], $request, $data);
    }

    /**
     * Create response
     *
     * @param array $parameters
     *
     * @return Response
     */
    protected function createResponse(array $parameters = [])
    {
        return new Response(
            $this->templating->render($this->parameters['view'], $parameters)
        );
    }

    /**
     * Get view parameters
     *
     * @param Request $request
     * @param Form $form
     *
     * @return array
     */
    protected function getViewParameters(Request $request, Form $form)
    {
        return ['form' => $form->createView()];
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

    /**
     * Get message for the given event
     *
     * @param Form $form
     * @param string $event Event: 'success', 'error', 'warning', 'notice'
     *
     * @return string
     */
    protected function getNotifyMessage(Form $form, $event)
    {
        return sprintf('elao_admin.notify.%s.%s', $this->parameters['alias'], $event);
    }
}
