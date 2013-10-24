<?php

namespace HappyR\LocationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class HappyRLocationExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        if ($config['geocoder_service'] != null) {
            if ($config['geocoder_service'] == 'happyr.geocoder') {
                $loader->load('geocoder.yml');
                $geocoderService = new Reference('happyr.location.geocoder_service');
            } else {
                $geocoderService = new Reference($config['geocoder_service']);
            }

            $container->getDefinition('happyr.location.location_type')
                ->replaceArgument(1, $geocoderService);
        }
    }
}
