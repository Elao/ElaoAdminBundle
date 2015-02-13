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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Elao\Bundle\AdminBundle\Action\Action;

/**
 * The default action for read pages
 */
class ReadAction extends Action
{
    /**
     * Template engine
     *
     * @var EngineInterface $templating
     */
    protected $templating;

    /**
     * Indject dependencies
     *
     * @param EngineInterface $templating
     */
    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse(Request $request)
    {
        $model = $this->getModel($request);

        return $this->createResponse($this->getViewParameters($request, $model));
    }

    /**
     * Get view parameters
     *
     * @param Request $request
     * @param mixed $model
     *
     * @return array
     */
    protected function getViewParameters(Request $request, $model)
    {
        return ['model' => $model];
    }

    /**
     * Get model from request
     *
     * @param Request $request
     *
     * @return mixed
     */
    protected function getModel(Request $request)
    {
        $model = $this->modelManager->find(['id' => $request->get('id')]);

        if (!$model) {
            throw new NotFoundHttpException;
        }

        return $model;
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
}
