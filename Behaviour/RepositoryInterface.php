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

interface RepositoryInterface {
    /**
     * Create a new instance of the model
     *
     * @return Object
     */
    public function create();

    /**
     * Persist the given model to the database
     *
     * @param Object $model
     */
    public function persist($model);

    /**
     * Remove the given model from the database
     *
     * @param Object $model
     */
    public function delete($model);
}
