<?php

namespace Elao\Bundle\AdminBundle\Action;

use Symfony\Component\HttpFoundation\Request;

/**
 * Create Action
 */
class CreateAction extends Action
{
    /**
     * The path used when redirecting the user after creation
     *
     * @var string
     */
    private $redirection;

    /**
     * {@inheritdoc}
     */
    public function getResponse(Request $request)
    {
        $model = $this->modelManager->getInstance();
        $form  = $this->createForm($this->formType, $model);

        if ($form->handleRequest($request)->isSubmitted()) {

            if ($form->isValid()) {

                $this->modelManager->persist($model)->flush();

                return $this->redirect($this->redirection);
            }
        }

        return ['form' => $form->createView()];
    }
}
