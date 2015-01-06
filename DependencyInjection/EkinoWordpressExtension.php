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

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

/**
 * Class EkinoWordpressExtension
 *
 * This is the bundle Symfony extension class
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class EkinoWordpressExtension extends Extension
{
    /**
     * Loads configuration
     *
     * @param array            $configs   A configuration array
     * @param ContainerBuilder $container Symfony container builder
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('orm.xml');
        $loader->load('services.xml');
        $loader->load('hooks.xml');

        if (isset($config['table_prefix'])) {
            $this->loadTablePrefix($container, $config['table_prefix']);
        }

        if (isset($config['wordpress_directory'])) {
            $this->loadWordpressDirectory($container, $config['wordpress_directory']);
        }

        if (isset($config['entity_manager'])) {
            $this->loadEntityManager($container, $config['entity_manager']);
        }

        if ($config['load_twig_extension']) {
            $loader->load('twig.xml');
        }

        if ($config['i18n_cookie_name']) {
            $container->setParameter('ekino.wordpress.i18n_cookie_name', $config['i18n_cookie_name']);
            $loader->load('i18n.xml');
        }

        $container->setParameter('ekino.wordpress.cookie_hash', $config['cookie_hash']);
        $container->setParameter('ekino.wordpress.firewall_name', $config['security']['firewall_name']);
        $container->setParameter('ekino.wordpress.login_url', $config['security']['login_url']);

        $container->setParameter($this->getAlias().'.backend_type_orm', true);
    }

    /**
     * Loads table prefix from configuration to doctrine table prefix subscriber event
     *
     * @param ContainerBuilder $container Symfony dependency injection container
     * @param string           $prefix    Wordpress table prefix
     */
    protected function loadTablePrefix(ContainerBuilder $container, $prefix)
    {
        $identifier = 'ekino.wordpress.subscriber.table_prefix_subscriber';

        $serviceDefinition = $container->getDefinition($identifier);
        $serviceDefinition->setArguments(array($prefix));

        $container->setDefinition($identifier, $serviceDefinition);
    }

    /**
     * Loads Wordpress directory from configuration
     *
     * @param ContainerBuilder $container Symfony dependency injection container
     * @param string           $directory Wordpress directory
     */
    protected function loadWordpressDirectory(ContainerBuilder $container, $directory)
    {
        $identifier = 'ekino.wordpress.wordpress';

        $serviceDefinition = $container->getDefinition($identifier);
        $serviceDefinition->addArgument($directory);

        $container->setDefinition($identifier, $serviceDefinition);
    }

    /**
     * @param ContainerBuilder       $container
     * @param EntityManagerInterface $em
     */
    protected function loadEntityManager(ContainerBuilder $container, $em)
    {
        $reference = new Reference(sprintf('doctrine.orm.%s_entity_manager', $em));

        $container->getDefinition('ekino.wordpress.manager.comment')->replaceArgument(0, $reference);
        $container->getDefinition('ekino.wordpress.manager.comment_meta')->replaceArgument(0, $reference);
        $container->getDefinition('ekino.wordpress.manager.link')->replaceArgument(0, $reference);
        $container->getDefinition('ekino.wordpress.manager.option')->replaceArgument(0, $reference);
        $container->getDefinition('ekino.wordpress.manager.post')->replaceArgument(0, $reference);
        $container->getDefinition('ekino.wordpress.manager.post_meta')->replaceArgument(0, $reference);
        $container->getDefinition('ekino.wordpress.manager.term')->replaceArgument(0, $reference);
        $container->getDefinition('ekino.wordpress.manager.term_relationships')->replaceArgument(0, $reference);
        $container->getDefinition('ekino.wordpress.manager.term_taxonomy')->replaceArgument(0, $reference);
        $container->getDefinition('ekino.wordpress.manager.user')->replaceArgument(0, $reference);
        $container->getDefinition('ekino.wordpress.manager.user_meta')->replaceArgument(0, $reference);
    }

    /**
     * Returns bundle alias name
     *
     * @return string
     */
    public function getAlias()
    {
        return 'ekino_wordpress';
    }
}
