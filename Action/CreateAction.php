<?php

namespace Elao\Bundle\MicroAdminBundle\Action;

use Symfony\Component\HttpFoundation\Request;
use Elao\Bundle\MicroAdminBundle\Behaviour\ActionInterface;

/**
 * Create Action
 */
class CreateAction implements ActionInterface
{
    public function getResponse(Request $request)
    {
        $model = $this->modelManager->getInstance();
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