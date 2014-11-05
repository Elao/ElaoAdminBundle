<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2014 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\Form\Data;

use Elao\Bundle\AdminBundle\Behaviour\FilterSetInterface;

/**
 * A set of filters
 */
class FilterSet implements FilterSetInterface
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array_filter(get_object_vars($this), function ($value) { return $value !== null; });
    }
}
