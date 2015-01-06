<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle;

use Ekino\WordpressBundle\DependencyInjection\Compiler\RegisterMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class EkinoWordpressBundle
 *
 * This is the main Symfony bundle class
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class EkinoWordpressBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $this->addRegisterMappingPass($container);
    }

    /**
     * @param ContainerBuilder $containerBuilder
     */
    public function addRegisterMappingPass(ContainerBuilder $containerBuilder)
    {
        $mappings = array(
            realpath(__DIR__.'/Resources/config/doctrine/model') => 'Ekino\WordpressBundle\Model',
        );

        $containerBuilder->addCompilerPass(RegisterMappingsPass::createOrmMappingDriver($mappings));
    }
}
