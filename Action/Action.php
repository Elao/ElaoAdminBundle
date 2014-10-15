<?php

namespace Elao\Bundle\AdminBundle\Action;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
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
     * Various configuration options
     *
     * @var array
     */
    protected $options;

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
     * Set options
     *
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }
}
