<?php

namespace Elao\Bundle\AdminBundle\DependencyInjection\Model;

use Symfony\Component\Config\Definition\Processor;

class Action
{
    /**
     * Type of action (parent service)
     *
     * @var ActionType
     */
    protected $type;

    /**
     * Administration (owner of the action)
     *
     * @var Administration
     */
    protected $administration;

    /**
     * Constructor
     *
     * @param Administration $administration
     * @param string $alias
     * @param array $options
     */
    public function __construct(ActionType $type, Administration $administration, array $options)
    {
        $this->type           = $type;
        $this->administration = $administration;
        $this->options        = (new Processor)->processConfiguration(
            $this->type->getConfiguration($this),
            ['action' => $options]
        );
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->type->getAlias();
    }

    /**
     * Get action service Id
     *
     * @return string
     */
    public function getServiceId()
    {
        return sprintf('admin_action.%s.%s', $this->administration->getNameLowerCase(), $this->getAlias());
    }

    /**
     * Get parent service Id
     *
     * @return string
     */
    public function getParentServiceId()
    {
        return $this->type->getServiceId();
    }

    /**
     * Get route
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->options['parameters'];
    }

    /**
     * Get route
     *
     * @return array
     */
    public function getRoute()
    {
        return $this->options['route'];
    }

    /**
     * Get administration
     *
     * @return Administration
     */
    public function getAdministration()
    {
        return $this->administration;
    }
}