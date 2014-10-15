<?php

namespace Elao\Bundle\AdminBundle\Action;

use Symfony\Component\HttpFoundation\Request;
use Elao\Bundle\AdminBundle\Behaviour\ActionInterface;

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