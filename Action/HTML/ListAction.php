<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2014 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\Action\HTML;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Elao\Bundle\AdminBundle\Action\Action;
use Elao\Bundle\AdminBundle\Behaviour\FilterSetInterface;

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
        if ($filterForm = $this->createFilterForm()) {
            $filterForm->handleRequest($request);
        }

        $filters = $this->getFilters($filterForm);
        $models  = $this->getModels($request, $filters);

        return $this->createResponse($this->getViewParameters($request, $models, $filterForm));
    }

    /**
     * Create filter form
     *
     * @return Form
     */
    protected function createFilterForm()
    {
        if (!$this->parameters['filters']['enabled']) {
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
        if (!$form) {
            return [];
        }

        $data = $form->getData();

        if ($data instanceof FilterSetInterface) {
            return $data->getFilters();
        }

        if (is_array($data)) {
            return array_filter($data, function ($value) {
                return $value !== null;
            });
        }

        throw new \Exception(sprintf('Unknown data type for form "%s".', $form->getName()));
    }

    /**
     * Get models
     *
     * @param Request $request
     * @param array $filters
     *
     * @return PaginationInterface|array
     */
    public function getModels(Request $request, array $filters = [])
    {
        if (!$this->parameters['pagination']['enabled']) {
            return $this->modelManager->findAll($filters);
        }

        return $this->paginate($request, $this->modelManager->getTarget($filters));
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
        $perPage = $this->parameters['pagination']['per_page'];

        return $this->paginator->paginate($target, $page, $perPage);
    }

    /**
     * Create response
     *
     * @param array $parameters
     *
     * @return Response
     */

    protected function createResponse(array $parameters = [])
    {
        return new Response(
            $this->templating->render($this->parameters['view'], $parameters)
        );
    }

    /**
     * Get view parameters
     *
     * @param Request $request
     * @param array|PaginationInterface $models
     * @param Form $filterForm
     *
     * @return array
     */
    protected function getViewParameters(Request $request, $models, Form $filterForm = null)
    {
        $parameters = [];

        if ($models instanceof PaginationInterface) {
            $parameters = ['pagination' => $models];
        } else {
            $parameters = ['models' => $models];
        }

        if ($filterForm) {
            $parameters['filters'] = $filterForm->createView();
        }

        return $parameters;
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
