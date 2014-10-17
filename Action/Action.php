<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2014 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\Action;

use Elao\Bundle\AdminBundle\Behaviour\ActionInterface;
use Elao\Bundle\AdminBundle\Behaviour\ModelManagerInterface;

/**
* An action with a model manager
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
     * Various configuration parameters
     *
     * @var array
     */
    protected $parameters;

    /**
     * Inject dependencies
     *
     * @param ModelManagerInterface $modelManager
     */
    public function setModelManager(ModelManagerInterface $modelManager)
    {
        $this->modelManager = $modelManager;
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
