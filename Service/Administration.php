<?php

namespace Elao\Bundle\MicroAdminBundle\Service;

use Elao\Bundle\MicroAdminBundle\Behaviour\AdministrationInterface;

/**
 * Administration
 */
class Administration implements AdministrationInterface
{
    /**
     * Model
     *
     * @var string
     */
    protected $model;

    /**
     * Model
     *
     * @var string
     */
    protected $controller;

    /**
     * Form types
     *
     * @var array
     */
    protected $formTypes;

    /**
     * Redirections
     *
     * @var array
     */
    protected $redirections;

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * {@inheritdoc}
     */
    public function setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * {@inheritdoc}
     */
    public function setFormType($formtype, $action)
    {
        $this->formTypes[$action] = $formtype;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormType($action)
    {
        if (!array_key_exists($action, $this->formTypes)) {
            throw new Exception(sprintf('No form is defined for action "%s".', $action));
        }

        return $this->formTypes[$action];
    }

    /**
     * {@inheritdoc}
     */
    public function setRedirection($action, $route, array $parameters = [])
    {
        $this->redirections[$action] = ['route' => $route, 'parameters' => $parameters];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirection($model, $action)
    {
        if (!array_key_exists($action, $this->redirections)) {
            throw new Exception(sprintf('No route is defined for action "%s".', $action));
        }

        return $this->redirections[$action];
    }
}