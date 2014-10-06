<?php

namespace Elao\Bundle\MicroAdminBundle\Action;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Elao\Bundle\MicroAdminBundle\Behaviour\ActionInterface;
use Elao\Bundle\MicroAdminBundle\Behaviour\ModelManagerInterface;

/**
* Action
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
     * View
     *
     * @var string
     */
    protected $view = 'ElaoMicroAdminBundle:Action:index.html.twig';

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
}