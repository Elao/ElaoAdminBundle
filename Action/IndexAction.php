<?php

namespace Elao\Bundle\AdminBundle\Action;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Elao\Bundle\AdminBundle\Behaviour\ActionInterface;

/**
 * Index Action
 */
class IndexAction extends Action
{
    public function getResponse(Request $request)
    {
        return new Response(
            $this->templating->render(
                $this->view,
                ['models' => $this->modelManager->findAll()]
            )
        );
    }
}