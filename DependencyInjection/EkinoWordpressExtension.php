<?php

namespace Ekino\WordpressBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

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
