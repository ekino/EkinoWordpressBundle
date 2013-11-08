<?php

namespace Ekino\WordpressBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration
 *
 * This class generates configuration settings tree
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Builds configuration tree
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder A tree builder instance
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ekino_wordpress');

        $rootNode
            ->children()
                ->scalarNode('table_prefix')->defaultValue('wp_')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}