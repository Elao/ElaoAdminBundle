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
        $model = $this->modelManager->find();
        $form  = $this->createForm($this->formType, $model);

        if ($form->handleRequest($request)->isSubmitted()) {

            if ($form->isValid()) {

                $this->modelManager->delete($model)->flush();

                return $this->redirect($this->redirection);
            }
        }

        return ['form' => $form->createView()];
    }
}
