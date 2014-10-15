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
use Symfony\Component\HttpFoundation\Response;

use Elao\Bundle\AdminBundle\Behaviour\ActionInterface;

/**
 * The default action for index pages
 */
class IndexAction extends Action
{
    /**
     * {@inheritdoc}
     */
    public function getResponse(Request $request)
    {
        return new Response(
            $this->templating->render(
                $this->parameters['view'],
                ['models' => $this->modelManager->findAll()]
            )
        );
    }
}
