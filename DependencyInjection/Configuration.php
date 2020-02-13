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

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration.
 *
 * This class generates configuration settings tree
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Builds configuration tree.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder A tree builder instance
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('ekino_wordpress');
        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            $rootNode = $treeBuilder->root('ekino_wordpress');
        }

        $rootNode
            ->children()
                ->scalarNode('table_prefix')->end()
                ->scalarNode('wordpress_directory')->defaultNull()->end()
                ->scalarNode('entity_manager')->end()
                ->booleanNode('load_twig_extension')->defaultFalse()->end()
                ->booleanNode('cookie_hash')->defaultNull()->end()
                ->scalarNode('i18n_cookie_name')->defaultFalse()->end()
                ->booleanNode('enable_wordpress_listener')->defaultTrue()->end()

                ->arrayNode('globals')
                    ->prototype('scalar')->defaultValue([])->end()
                ->end()

                ->arrayNode('security')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('firewall_name')->defaultValue('secured_area')->end()
                        ->scalarNode('login_url')->defaultValue('/wp-login.php')->end()
                    ->end()
                ->end()
            ->end();

        $this->addServicesSection($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    protected function addServicesSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('services')
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('comment')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('class')->cannotBeEmpty()->defaultValue('Ekino\WordpressBundle\Entity\Comment')->end()
                            ->scalarNode('manager')->cannotBeEmpty()->defaultValue('ekino.wordpress.manager.comment_default')->end()
                            ->scalarNode('repository_class')->cannotBeEmpty()->defaultValue('Ekino\WordpressBundle\Repository\CommentRepository')->end()
                        ->end()
                    ->end()
                    ->arrayNode('comment_meta')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('class')->cannotBeEmpty()->defaultValue('Ekino\WordpressBundle\Entity\CommentMeta')->end()
                            ->scalarNode('manager')->cannotBeEmpty()->defaultValue('ekino.wordpress.manager.comment_meta_default')->end()
                            ->scalarNode('repository_class')->cannotBeEmpty()->defaultValue('Ekino\WordpressBundle\Repository\CommentMetaRepository')->end()
                        ->end()
                    ->end()
                    ->arrayNode('link')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('class')->cannotBeEmpty()->defaultValue('Ekino\WordpressBundle\Entity\Link')->end()
                            ->scalarNode('manager')->cannotBeEmpty()->defaultValue('ekino.wordpress.manager.link_default')->end()
                            ->scalarNode('repository_class')->cannotBeEmpty()->defaultValue('Ekino\WordpressBundle\Repository\LinkRepository')->end()
                        ->end()
                    ->end()
                    ->arrayNode('option')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('class')->cannotBeEmpty()->defaultValue('Ekino\WordpressBundle\Entity\Option')->end()
                            ->scalarNode('manager')->cannotBeEmpty()->defaultValue('ekino.wordpress.manager.option_default')->end()
                            ->scalarNode('repository_class')->cannotBeEmpty()->defaultValue('Ekino\WordpressBundle\Repository\OptionRepository')->end()
                        ->end()
                    ->end()
                    ->arrayNode('post')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('class')->cannotBeEmpty()->defaultValue('Ekino\WordpressBundle\Entity\Post')->end()
                            ->scalarNode('manager')->cannotBeEmpty()->defaultValue('ekino.wordpress.manager.post_default')->end()
                            ->scalarNode('repository_class')->cannotBeEmpty()->defaultValue('Ekino\WordpressBundle\Repository\PostRepository')->end()
                        ->end()
                    ->end()
                    ->arrayNode('post_meta')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('class')->cannotBeEmpty()->defaultValue('Ekino\WordpressBundle\Entity\PostMeta')->end()
                            ->scalarNode('manager')->cannotBeEmpty()->defaultValue('ekino.wordpress.manager.post_meta_default')->end()
                            ->scalarNode('repository_class')->cannotBeEmpty()->defaultValue('Ekino\WordpressBundle\Repository\PostMetaRepository')->end()
                        ->end()
                    ->end()
                    ->arrayNode('term')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('class')->cannotBeEmpty()->defaultValue('Ekino\WordpressBundle\Entity\Term')->end()
                            ->scalarNode('manager')->cannotBeEmpty()->defaultValue('ekino.wordpress.manager.term_default')->end()
                            ->scalarNode('repository_class')->cannotBeEmpty()->defaultValue('Ekino\WordpressBundle\Repository\TermRepository')->end()
                        ->end()
                    ->end()
                    ->arrayNode('term_relationships')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('class')->cannotBeEmpty()->defaultValue('Ekino\WordpressBundle\Entity\TermRelationships')->end()
                            ->scalarNode('manager')->cannotBeEmpty()->defaultValue('ekino.wordpress.manager.term_relationships_default')->end()
                            ->scalarNode('repository_class')->cannotBeEmpty()->defaultValue('Ekino\WordpressBundle\Repository\TermRelationshipsRepository')->end()
                        ->end()
                    ->end()
                    ->arrayNode('term_taxonomy')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('class')->cannotBeEmpty()->defaultValue('Ekino\WordpressBundle\Entity\TermTaxonomy')->end()
                            ->scalarNode('manager')->cannotBeEmpty()->defaultValue('ekino.wordpress.manager.term_taxonomy_default')->end()
                            ->scalarNode('repository_class')->cannotBeEmpty()->defaultValue('Ekino\WordpressBundle\Repository\TermTaxonomyRepository')->end()
                        ->end()
                    ->end()
                    ->arrayNode('user')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('class')->cannotBeEmpty()->defaultValue('Ekino\WordpressBundle\Entity\User')->end()
                            ->scalarNode('manager')->cannotBeEmpty()->defaultValue('ekino.wordpress.manager.user_default')->end()
                            ->scalarNode('repository_class')->cannotBeEmpty()->defaultValue('Ekino\WordpressBundle\Repository\UserRepository')->end()
                        ->end()
                    ->end()
                    ->arrayNode('user_meta')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('class')->cannotBeEmpty()->defaultValue('Ekino\WordpressBundle\Entity\UserMeta')->end()
                            ->scalarNode('manager')->cannotBeEmpty()->defaultValue('ekino.wordpress.manager.user_meta_default')->end()
                            ->scalarNode('repository_class')->cannotBeEmpty()->defaultValue('Ekino\WordpressBundle\Repository\UserMetaRepository')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
