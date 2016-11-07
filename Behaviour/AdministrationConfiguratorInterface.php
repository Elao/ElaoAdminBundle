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

use Symfony\Component\Config\Definition\Builder\NodeBuilder;

/**
 * Complete administrations configuration.
 */
interface AdministrationConfiguratorInterface
{
    /**
     * Configure node definition.
     *
     * @param NodeBuilder $node
     */
    public function configure(NodeBuilder $node);
}
