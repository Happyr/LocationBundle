<?php

namespace Happyr\LocationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('happyr_location');

        $rootNode->children()
            ->scalarNode('geocoder_service')->defaultNull()->end()
            ->booleanNode('enable_excessive_geocoder')->defaultFalse()->end()
            ->end();

        return $treeBuilder;
    }
}
