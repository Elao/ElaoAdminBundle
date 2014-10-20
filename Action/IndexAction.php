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
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Knp\Component\Pager\Paginator;

/**
 * The default action for index pages
 */
class IndexAction extends Action
{
    /**
     * Template engine
     *
     * @var EngineInterface $templating
     */
    protected $templating;

    /**
     * Paginator
     *
     * @var Knp\Component\Pager\PaginatorInterface $paginator
     */
    protected $paginator;

    /**
     * Indject dependencies
     *
     * @param EngineInterface $templating
     */
    public function __construct(EngineInterface $templating, Paginator $paginator)
    {
        $this->templating = $templating;
        $this->paginator  = $paginator;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse(Request $request)
    {
        $pagination = $this->paginator->paginate(
            $this->modelManager->getTarget(),
            $request->query->get('page', 1),
            $this->parameters['per_page']
        );

        return new Response(
            $this->templating->render(
                $this->parameters['view'],
                ['models' => $pagination]
            )
        );
    }
}
