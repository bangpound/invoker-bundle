<?php

namespace Bangpound\Bundle\InvokerBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class InvokerCompilerPass implements CompilerPassInterface
{

    /**
     * {@inheritDocs}
     */
    public function process(ContainerBuilder $container)
    {
        $commandServices = $container->findTaggedServiceIds('bangpound_invoker.server');

        foreach ($commandServices as $id => $tags) {
            $definition = $container->getDefinition($id);

            if (!$definition->isPublic()) {
                throw new \InvalidArgumentException(sprintf('The service "%s" tagged "bangpound_invoker.server" must be public.', $id));
            }

            if ($definition->isAbstract()) {
                throw new \InvalidArgumentException(sprintf('The service "%s" tagged "bangpound_invoker.server" must not be abstract.', $id));
            }

            $container->getDefinition('bangpound_invoker.command.invoker')->addMethodCall('addBuilder', array(new Reference($id)));
        }
    }
}
