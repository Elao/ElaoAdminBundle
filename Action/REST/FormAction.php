<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2014 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\Action\REST;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

/**
 * The default action for create and update pages
 */
abstract class FormAction extends Action
{
    /**
     * Default success code
     */
    static public $successCode = 200;

    /**
     * Default error code
     */
    static public $errorCode = 400;

    /**
     * Form factory
     *
     * @var FormFactoryInterface $formFactory
     */
    protected $formFactory;

    /**
     * Indject dependencies
     *
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse(Request $request)
    {
        $format = $this->getFormat($request);
        $model  = $this->getModel($request);
        $form   = $this->createForm($model);

        $form->handleRequest($request);

        if (!$form->isValid()) {
            $this->onFormInvalid($form);

            return $this->createResponse($this->getErrorViewParameters($request, $form), $this->errorCode, $format);
        }

        $this->onFormValid($form);

        return $this->createResponse($this->getSuccessViewParameters($request, $form), $this->successCode, $format);
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
        return $this->formFactory->create($this->getFormType($this->parameters['form_type']), $model);
    }

    /**
     * On form valid
     *
     * @param Form $form
     */
    protected function onFormValid(Form $form)
    {
        $this->modelManager->persist($form->getData());
    }

    /**
     * On form invalid
     *
     * @param Form $form
     */
    protected function onFormInvalid(Form $form)
    {
    }

    /**
     * Get successview parameters
     *
     * @param Request $request
     * @param mixed $model
     *
     * @return array
     */
    protected function getSuccessViewParameters(Request $request, Form $form)
    {
        return ['model' => $form->getData()];
    }

    /**
     * Get successview parameters
     *
     * @param Request $request
     * @param mixed $model
     *
     * @return array
     */
    protected function getErrorViewParameters(Request $request, Form $form)
    {
        return ['errors' => $form->getErrors()];
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
