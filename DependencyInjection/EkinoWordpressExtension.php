<?php

namespace Ekino\WordpressBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
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

        if (isset($config['table_prefix'])) {
            $this->loadTablePrefix($container, $config['table_prefix']);
        }
    }

    /**
     * Loads table prefix from configuration to doctrine table prefix subscriber event
     *
     * @param ContainerBuilder $container Symfony dependency injection container
     * @param string           $prefix    Wordpress table prefix
     */
    protected function loadTablePrefix(ContainerBuilder $container, $prefix)
    {
        $identifier = 'ekino.wordpress.listener.table_prefix_subscriber';

        $serviceDefinition = $container->getDefinition($identifier);
        $serviceDefinition->setArguments(array($prefix));

        $container->setDefinition($identifier, $serviceDefinition);
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
