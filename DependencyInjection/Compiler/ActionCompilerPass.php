<?php

namespace Elao\Bundle\MicroAdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Action compiler pass
 */
class ActionCompilerPass implements CompilerPassInterface
{
    /**
     * Service tag
     */
    const TAG = 'elao_micro_admin.action';

    /**
     * Admin Loader service Id
     */
    const LOADER = 'elao_micro_admin.routing_loader';

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(static::LOADER)) {
            return;
        }

        $definition = $container->getDefinition(static::LOADER);
        $services   = $container->findTaggedServiceIds(static::TAG);

        foreach ($services as $id => $attributes) {
            $definition->addMethodCall('addAction', [new Reference($id), $attributes["alias"]]);
        }
    }
}