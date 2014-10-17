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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * The default action for create pages
 */
class CreateAction extends Action
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
     * Indject dependencies
     *
     * @param EngineInterface $templating
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(EngineInterface $templating, FormFactoryInterface $formFactory)
    {
        $this->templating  = $templating;
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse(Request $request)
    {
        $model = $this->modelManager->create();
        $form  = $this->formFactory->create($this->getFormType($this->parameters['form_type']), $model);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->modelManager->persist($model)->flush();

            return $this->redirect(
                $this->generateUrl(
                    $this->parameters['route']['name'],
                    $this->parameters['route']['parameters']
                )
            );
        }

        return new Response(
            $this->templating->render(
                $this->parameters['view'],
                ['form' => $form->createView()]
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
