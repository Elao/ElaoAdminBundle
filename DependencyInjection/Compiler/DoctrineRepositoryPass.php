<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2016 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class DoctrineRepositoryPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('elao_admin.repository_factory.doctrine')) {
            return;
        }

        $factoryDefinition = $container->findDefinition('elao_admin.repository_factory.doctrine');
        $repositories = $container->findTaggedServiceIds('elao_admin.repository.doctrine');

        foreach ($repositories as $id => $tags) {
            $repositoryDefinition = $container->findDefinition($id);
            $factoryDefinition->addMethodCall('registerServiceRepository', [$repositoryDefinition->getArgument(0), $id]);
        }
    }
}
