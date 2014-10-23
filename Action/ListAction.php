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
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * The default action for list pages
 */
class ListAction extends Action
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
        $target     = $this->modelManager->getTarget();
        $pagination = $this->paginate($request, $target);

        return $this->createResponse($pagination);
    }

    /**
     * Paginate the query/list of item
     *
     * @param Request $request
     * @param mixed $target
     *
     * @return PaginationInterface
     */
    protected function paginate(Request $request, $target)
    {
        $page    = $request->query->get('page', 1);
        $perPage = $this->parameters['per_page'];

        return $this->paginator->paginate($target, $page, $perPage);
    }

    /**
     * Create response
     *
     * @param PaginationInterface $pagination
     * @param array $parameters
     *
     * @return Response
     */
    protected function createResponse(PaginationInterface $pagination, array $parameters = [])
    {
        return new Response(
            $this->templating->render(
                $this->parameters['view'],
                array_merge(
                    ['pagination' => $pagination],
                    $parameters
                )
            )
        );
    }
}
