<?php

namespace Elao\Bundle\MicroAdminBundle\Behaviour;

/**
 * Model manager interface
 */
interface ModelManagerInterface
{
    /**
     * Create a new instance of the model
     *
     * @return mixed $model
     */
    public function getInstance();

    /**
     * Persist a model
     *
     * @param mixed $model
     *
     * @return ModelManagerInterface
     */
    public function persist($model);

    /**
     * Delete a model
     *
     * @param mixed $model
     *
     * @return ModelManagerInterface
     */
    public function delete($model);

    /**
     * Flush changes
     *
     * @return ModelManagerInterface
     */
    public function flush();
}