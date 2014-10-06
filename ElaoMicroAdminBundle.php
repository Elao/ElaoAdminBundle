<?php

namespace Elao\Bundle\MicroAdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Elao\Bundle\MicroAdminBundle\DependencyInjection\Compiler\ActionCompilerPass;

/**
 * Elao Micro Admin bundle
 */
class ElaoMicroAdminBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        //$container->addCompilerPass(new ActionCompilerPass);
    }
}