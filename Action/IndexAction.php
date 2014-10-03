<?php

namespace Elao\Bundle\MicroAdminBundle\Action;

use Symfony\Component\HttpFoundation\Request;
use Elao\Bundle\MicroAdminBundle\Behaviour\ActionInterface;

/**
 * Index Action
 */
class IndexAction implements ActionInterface
{
    public function getResponse(Request $request)
    {
        return ['models' => $this->modelManager->findAll()];
    }
}