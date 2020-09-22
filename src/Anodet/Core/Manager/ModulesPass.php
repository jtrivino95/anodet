<?php
/**
 * Created by PhpStorm.
 * User: jtrivino
 * Date: 13/01/17
 * Time: 11:41
 */

namespace Anodet\Core\Manager;


use Anodet\Core\Contracts\Module;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ModulesPass implements CompilerPassInterface
{

    /**
     * Add modules IDs to builder $modules attribute
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('builder')) {
            $definition = $container->findDefinition('builder');
        } else {
            throw new Exception("Unable to find Builder service!");
        }

        foreach (Module::TYPES as $type) {

            $taggedServices = $container->findTaggedServiceIds($type); // Find IDs of transporters, analyzers...

            foreach ($taggedServices as $serviceID => $tags) {

                $code = $tags[0]['code'];

                $definition->addMethodCall('addModuleId', array($code, $type, $serviceID));

            }

        }
    }
}