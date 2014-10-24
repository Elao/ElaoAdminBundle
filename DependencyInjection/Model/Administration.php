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

/**
 * Administration
 */
class Administration
{
    /**
     * Name
     *
     * @var string
     */
    protected $name;

    /**
     * Options
     *
     * @var array
     */
    protected $options;

    /**
     * Construct
     *
     * @param string $name
     * @param array $options
     */
    public function __construct($name, array $options)
    {
        $this->name    = $name;
        $this->options = $options;
        $this->actions = [];
    }

    /**
     * Process actions
     *
     * @param array $actionTypes
     */
    public function processActions($actionTypes)
    {
        foreach ($this->options['actions'] as $alias => $actionOptions) {

            if (!array_key_exists($alias, $actionTypes)) {
                throw new \Exception(sprintf(
                    'Unkown action "%s", availables actions are: %s',
                    $alias,
                    join(', ', array_keys($actionTypes))
                ));
            }

            $this->actions[$alias] = new Action($actionTypes[$alias], $this, $actionOptions);
        }

        unset($this->options['actions']);

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get model class name
     *
     * @return string
     */
    public function getModel()
    {
        return $this->options['model'];
    }

    /**
     * Get model manager service Id
     *
     * @return string
     */
    public function getManager()
    {
        return $this->options['manager'];
    }

    /**
     * Get templates directory
     *
     * @return string
     */
    public function getTemplatesDirectory()
    {
        return $this->options['templates'];
    }

    /**
     * Get model manager service Id
     *
     * @return string
     */
    public function getManagerId()
    {
        return sprintf('model_manager.%s', $this->name);
    }

    /**
     * Get actions
     *
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Get name in lower case (for route names)
     *
     * @return string
     */
    public function getNameLowerCase()
    {
        return strtolower(preg_replace('~(?<=\\w)([A-Z])~', '_$1', $this->name));
    }

    /**
     * Get name in lower case (for url)
     *
     * @return string
     */
    public function getNameUrl()
    {
        return urlencode(strtolower(preg_replace('~(?<=\\w)([A-Z])~', '-$1', $this->name)));
    }
}
