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

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Elao\Bundle\AdminBundle\Behaviour\FilterSetInterface;
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
     * Form factory
     *
     * @var FormFactoryInterface $formFactory
     */
    protected $formFactory;

    /**
     * Paginator
     *
     * @var Knp\Component\Pager\PaginatorInterface $paginator
     */
    protected $paginator;

    /**
     * Inject dependencies
     *
     * @param EngineInterface $templating
     * @param FormFactoryInterface $formFactory
     * @param Paginator $paginator
     */
    public function __construct(EngineInterface $templating, FormFactoryInterface $formFactory, Paginator $paginator)
    {
        $this->templating  = $templating;
        $this->formFactory = $formFactory;
        $this->paginator   = $paginator;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse(Request $request)
    {
        $filterForm = $this->createFilterForm();

        if ($filterForm) {
            $filterForm->handleRequest($request);
        }

        $filters    = $this->getFilters($filterForm);
        $target     = $this->modelManager->getTarget($filters);
        $pagination = $this->paginate($request, $target);

        return $this->createResponse($pagination, $filterForm);
    }

    /**
     * Create filter form
     *
     * @return Form
     */
    protected function createFilterForm()
    {
        if (!$this->parameters['filters']) {
            return null;
        }

        $formType = $this->getFormType($this->parameters['filters']['form_type']);
        $data     = $this->getFormData($this->parameters['filters']['data']);

        return $this->formFactory
            ->create($formType, $data)
            ->add('reset', 'reset')
            ->add('submit', 'submit');
    }

    /**
     * Get form data
     *
     * @param mixed $data
     *
     * @return array
     */
    protected function getFormData($data)
    {
        if (!$data) {
            return [];
        }

        if (!class_exists($data)) {
            throw new \Exception(sprintf('Unknow form data class "%s".', $data));
        }

        return new $data;
    }

    /**
     * Get filters
     *
     * @param Form $form
     *
     * @return array
     */
    protected function getFilters(Form $form = null)
    {
        if (!$form) { return []; }

        $data = $form->getData();

        if ($data instanceof FilterSetInterface) {
            return $data->getFilters();
        }

        if (is_array($data)) {
            return array_filter($data, function ($value) { return $value !== null; });
        }

        throw new \Exception(sprintf('Unknown data type for form "%s".', $form->getName()));
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
    protected function createResponse(PaginationInterface $pagination, Form $filterForm = null, array $parameters = [])
    {
        $defaultParameters = ['pagination' => $pagination];

        if ($filterForm) {
            $defaultParameters['filters'] = $filterForm->createView();
        }

        return new Response(
            $this->templating->render(
                $this->parameters['view'],
                array_merge($defaultParameters,  $parameters)
            )
        );
    }

    /**
     * Get form type
     *
     * @param string $formType
     *
     * @return string|Symfony\Component\Form\AbstractType
     */
    protected function getFormType($formType)
    {
        return class_exists($formType) ? new $formType : $formType;
    }
}
