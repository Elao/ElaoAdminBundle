<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2014 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\DependencyInjection\Model;

use Symfony\Component\Config\Definition\Processor;

/**
 * Action
 */
class Action
{
    /**
     * Alias
     *
     * @var string
     */
    protected $alias;

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
     * @param string $alias
     * @param ActionType $type
     * @param Administration $administration
     * @param array $options
     */
    public function __construct($alias, ActionType $type, Administration $administration, array $options)
    {
        $this->alias          = $alias;
        $this->type           = $type;
        $this->administration = $administration;

        $this->options        = (new Processor)->processConfiguration(
            $this->type->getConfiguration($this),
            ['options' => $options]
        );

        $this->options['parameters'] = array_merge(
            $this->options['parameters'],
            $this->getDefaultParameters()
        );
    }

    /**
     * Get default parameters
     *
     * @return array
     */
    private function getDefaultParameters()
    {
        return [
            'alias'          => $this->getAlias(),
            'administration' => [
                'name'  => $this->administration->getName(),
                'alias' => $this->administration->getNameLowerCase(),
                'url'   => $this->administration->getNameUrl(),
            ]
        ];
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
     * Is action secure?
     *
     * @return boolean
     */
    public function isSecure()
    {
        return isset($this->options['security']);
    }

    /**
     * Get security
     *
     * @return array
     */
    public function getSecurity()
    {
        return $this->options['security'];
    }

    /**
     * Get redirection
     *
     * @return array
     */
    public function getRedirection()
    {
        return $this->options['redirection'];
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
