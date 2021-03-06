<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2016 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\Behaviour;

/**
 * Adds Pagination to RepositoryInterface
 */
interface PaginableRepositoryInterface {
    /**
     * Create an iterable for pagination
     *
     * @param array $parameter Filters
     *
     * @param array|QueryBuilder $model
     */
    public function paginate(array $parameters = []);
}
