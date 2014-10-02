<?php

namespace Elao\Bundle\MicroAdminBundle\Service;

/**
 * AdminConfig
 */
class AdminConfig implements AdminConfigInterface
{
    /**
     * Model
     *
     * @var string
     */
    protected $classname;

    /**
     * Form types
     *
     * @var array
     */
    protected $formTypes;

    /**
     * Routes
     *
     * @var array
     */
    protected $routes;

    /**
     * Classname
     *
     * @param string $classname
     */
    public function __construct($classname)
    {
        $this->classname = $classname;
    }

    /**
     * Get model classname
     *
     * @return string
     */
    public function getClassname()
    {
        return $this->classname;
    }

    /**
     * Get a new instance of the model
     *
     * @return mixed
     */
    public function getFormType($action)
    {
        if (!array_key_exists($action, $this->formTypes)) {
            throw new Exception(sprintf('No form is defined for action "%s".', $action));
        }

        return $this->formTypes[$action];
    }

    /**
     * Get the success redirection url for the given model and action
     *
     * @return mixed
     */
    public function getRedirection($model, $action)
    {
        // TODO
    }
}