<?php

namespace Elao\Bundle\MicroAdminBundle\Action;

use Symfony\Component\HttpFoundation\Request;
use Elao\Bundle\MicroAdminBundle\Behaviour\ActionInterface;

/**
 * Read Action
 */
class ReadAction extends Action
{
    public function getResponse(Request $request)
    {
        return ['model' => $this->modelManager->find()];
    }
}