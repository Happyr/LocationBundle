<?php

namespace Happyr\LocationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Michal Dabrowski <dabrowski@brillante.pl>
 */
class GeocoderPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {

        if (!$container->getParameter('happyr_location.enable_excessive_geocoder') ||
            !$container->hasDefinition('bazinga_geocoder.geocoder')) {
            return;
        }

        $definition = $container->getDefinition('bazinga_geocoder.geocoder');
        $definition->setClass($container->getParameter('happyr.location.geocoder.excessive.class'));
        $container->setAlias('happyr.location.geocoder.excessive', 'bazinga_geocoder.geocoder');
    }
}
