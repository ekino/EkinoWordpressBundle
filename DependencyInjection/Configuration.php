<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
        /**
        ->arrayNode('profiler')
            ->addDefaultsIfNotSet()
            ->fixXmlConfig('container_type', 'container_types')
            ->children()
                ->scalarNode('enabled')->defaultValue('%kernel.debug%')->end()
            ->end()
        ->end()
         */
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ekino_wordpress');

        $rootNode
            ->children()
                ->scalarNode('table_prefix')->end()
                ->scalarNode('wordpress_directory')->end()
                ->scalarNode('entity_manager')->end()
                ->booleanNode('load_twig_extension')->defaultFalse()->end()
                ->booleanNode('cookie_hash')->defaultNull()->end()
                ->scalarNode('i18n_cookie_name')->defaultFalse()->end()

                ->arrayNode('security')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('firewall_name')->defaultValue('secured_area')->end()
                        ->scalarNode('login_url')->defaultValue('/wp-login.php')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
