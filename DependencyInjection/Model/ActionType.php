<?php

namespace Elao\Bundle\AdminBundle\DependencyInjection\Model;

class ActionType
{
    /**
     * Action service Id
     *
     * @var string
     */
    protected $id;

    /**
     * Action alias
     *
     * @var string
     */
    protected $alias;

    /**
     * Action configuration class name
     *
     * @var string
     */
    protected $configClass;

    /**
     * Constructor
     *
     * @param string $id
     * @param string $alias
     * @param string $configClass
     */
    public function __construct($id, $alias, $configClass)
    {
        $this->id          = $id;
        $this->alias       = $alias;
        $this->configClass = $configClass;
    }

    /**
     * Get service Id
     *
     * @return string
     */
    public function getServiceId()
    {
        return $this->id;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Create instance of the Configuration for the given administration
     *
     * @param string $name Name of the administration
     *
     * @return Symfony\Component\Config\Definition\ConfigurationInterface
     */
    public function getConfiguration(Action $action)
    {
        return new $this->configClass($action, $this->id);
    }
}