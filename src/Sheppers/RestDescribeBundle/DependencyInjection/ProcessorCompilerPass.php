<?php

namespace Sheppers\RestDescribeBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class ProcessorCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('sheppers_rest_describe.describer')) {
            return;
        }

        $definition = $container->getDefinition('sheppers_rest_describe.describer');
        $taggedServices = $container->findTaggedServiceIds('sheppers_rest_describe.processor');

        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall('addProcessor', array(new Reference($id)));
        }
    }
}
