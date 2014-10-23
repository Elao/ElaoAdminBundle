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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * The default action for update pages
 */
class UpdateAction extends FormAction
{
    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    protected function createSuccessResponse()
    {
        return $this->redirect(
            $this->generateUrl(
                $this->parameters['route']['name'],
                $this->parameters['route']['parameters']
            )
        );
    }
}
