<?php

namespace Sheppers\RestDescribeBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Sheppers\RestDescribeBundle\DependencyInjection\ProcessorCompilerPass;

class SheppersRestDescribeBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ProcessorCompilerPass());
    }
}
