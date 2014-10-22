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
use Elao\Bundle\AdminBundle\Behaviour\ActionInterface;

/**
 * Delete Action
 */
class DeleteAction extends Action
{
    public function getResponse(Request $request)
    {
        $model = $this->modelManager->find(['id' => $request->get('id')]);
        $this->modelManager->delete($model);

        return $this->redirect(
            $this->generateUrl(
                $this->parameters['route']['name'],
                $this->parameters['route']['parameters']
            )
        );
    }
}
