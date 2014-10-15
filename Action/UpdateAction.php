<?php

namespace Elao\Bundle\AdminBundle\Action;

use Symfony\Component\HttpFoundation\Request;
use Elao\Bundle\AdminBundle\Behaviour\ActionInterface;

/**
 * Update Action
 */
class UpdateAction extends Action
{
    public function getResponse(Request $request)
    {
        $model = $this->modelManager->find();
        $form  = $this->createForm($this->formType,  $model);

        if ($form->handleRequest($request)->isSubmitted()) {

            if ($form->isValid()) {

                $this->modelManager->persist($model)->flush();

                return $this->redirect($this->redirection);
            }
        }

        return ['form' => $form->createView()];
    }
}