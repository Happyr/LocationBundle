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
        $treeBuilder = new TreeBuilder('happyr_location');
        if (\method_exists(TreeBuilder::class, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // Symfony < 4.2 code
            $rootNode = $treeBuilder->root('happyr_location');
        }

        $rootNode->children()
            ->scalarNode('geocoder_service')->isRequired()->cannotBeEmpty()->end()
            ->end();

        return $treeBuilder;
    }
}
