<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2014 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\Behaviour;

/**
 * The model manager used to persist data
 */
interface ModelManagerInterface
{
    /**
     * Find a model
     *
     * @param array $parameters
     *
     * @return mixed
     */
    public function find(array $parameters = []);

    /**
     * Find all models
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function findAll(array $parameters = []);

    /**
     * Create a new instance of the model
     *
     * @return mixed $model
     */
    public function create();

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
}
