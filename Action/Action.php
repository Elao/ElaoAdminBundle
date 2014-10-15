<?php

namespace Elao\Bundle\AdminBundle\Action;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\ParametersResolver\ParametersResolverInterface;
use Elao\Bundle\AdminBundle\Behaviour\ActionInterface;
use Elao\Bundle\AdminBundle\Behaviour\ModelManagerInterface;

/**
* An action with a model manager and a templating EngineInterface
*/
abstract class Action implements ActionInterface
{
    /**
     * Model manager
     *
     * @var ModelManagerInterface
     */
    protected $modelManager;

    /**
     * Template engine
     *
     * @var EngineInterface $templating
     */
    protected $templating;

    /**
     * Various configuration parameters
     *
     * @var array
     */
    protected $parameters;

    /**
     * Set model manager
     *
     * @param ModelManagerInterface $modelManager
     *
     * @return ActionInterface
     */
    public function setModelManager(ModelManagerInterface $modelManager)
    {
        $this->modelManager = $modelManager;

        return $this;
    }

    /**
     * Set templating
     *
     * @param EngineInterface $templating
     *
     * @return ActionInterface
     */
    public function setTemplating(EngineInterface $templating)
    {
        $this->templating = $templating;

        return $this;
    }

    /**
     * Set parameters
     *
     * @param array $parameters
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }
}
