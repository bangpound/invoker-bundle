<?php

namespace Bangpound\Bundle\InvokerBundle;

use Bangpound\Bundle\InvokerBundle\DependencyInjection\CompilerPass\InvokerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BangpoundInvokerBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new InvokerCompilerPass());
    }
}
