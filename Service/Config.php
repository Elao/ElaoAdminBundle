<?php

namespace Elao\Bundle\MicroAdminBundle\Service;

/**
 * Configuration
 */
class Config implements ConfigInterface
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
     * {@inheritdoc}
     */
    public function getClassname()
    {
        return $this->classname;
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
    public function getRedirection($model, $action)
    {
        // TODO
    }
}