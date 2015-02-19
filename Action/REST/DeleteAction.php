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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Form;

/**
 * The delete action for update pages
 */
class DeleteAction extends FormAction
{
    /**
     * Default success code
     */
    static public $successCode = 204;

    /**
     * {@inheritdoc}
     */
    protected function getModel(Request $request)
    {
        $model = $this->modelManager->find($request->get('_route_params'));

        if (!$model) {
            throw new NotFoundHttpException;
        }

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSuccessViewParameters(Request $request, Form $form)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    protected function onFormValid(Form $form)
    {
        $this->modelManager->delete($form->getData());
    }
}
