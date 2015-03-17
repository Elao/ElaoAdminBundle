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

use Doctrine\Common\Inflector\Inflector;

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
     * @param string $this->name
     * @param array $options
     */
    public function __construct($name, array $options)
    {
        $this->name    = $name;
        $this->options = $options;
        $this->actions = [];
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName($plural = null)
    {
        return static::applyPlural($this->name, $plural);
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
    public function getModelManager()
    {
        return $this->options['model_manager'];
    }

    /**
     * Get model manager service Id
     *
     * @return string
     */
    public function getModelManagerId()
    {
        return sprintf('model_manager.%s', $this->name);
    }

    /**
     * Get route resolver service Id
     *
     * @return string
     */
    public function getRouteResolver()
    {
        return $this->options['route_resolver'];
    }

    /**
     * Get route resolver service Id
     *
     * @return string
     */
    public function getRouteResolverId()
    {
        return sprintf('route_resolver.%s', $this->name);
    }

    /**
     * Add action
     *
     * @param Action $action
     */
    public function addAction(Action $action)
    {
        $this->actions[$action->getAlias()] = $action;
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
    public function getNameLowerCase($plural = null)
    {
        return Inflector::tableize(static::applyPlural($this->name, $plural));
    }

    /**
     * Get name in lower case (for route names)
     *
     * @return string
     */
    public function getNameUpperWordCase($plural = null)
    {
        return Inflector::classify(static::applyPlural($this->name, $plural));
    }

    /**
     * Get name in lower case (for route names)
     *
     * @return string
     */
    public function getNameCamelCase($plural = null)
    {
        return Inflector::classify(static::applyPlural($this->name, $plural));
    }

    /**
     * Get name in lower case (for url)
     *
     * @return string
     */
    public function getNameUrl($plural = null)
    {
        return urlencode(str_replace('_', '-', $this->getNameLowerCase($plural)));
    }

    /**
     * Apply plural rule
     *
     * @param string $word
     * @param boolean|null $plural
     *
     * @return string
     */
    public static function applyPlural($word, $plural = null)
    {
        if ($plural === null) {
            return $word;
        }

        return $plural ? Inflector::pluralize($word) : Inflector::singularize($word);
    }
}
