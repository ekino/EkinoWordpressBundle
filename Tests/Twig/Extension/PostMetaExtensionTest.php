<?php

/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle\Tests\Twig\Extension;

use Ekino\WordpressBundle\Twig\Extension\PostMetaExtension;

/**
 * Class PostMetaExtensionTest
 *
 * @author Xavier Coureau <xav.is@2cool4school.fr>
 */
class PostMetaExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $postMetaManager;

    /**
     * @var PostMetaExtension
     */
    protected $extension;

    protected function setUp()
    {
        if (!class_exists('\Twig_Extension')) {
            $this->markTestSkipped('Twig is not enabled');
        }

        $this->postMetaManager = $this->getMockBuilder('Ekino\WordpressBundle\Manager\PostMetaManager')->disableOriginalConstructor()->getMock();
        $this->extension = new PostMetaExtension($this->postMetaManager);
    }

    public function testGetName()
    {
        $this->assertEquals('ekino_wordpress_post_meta', $this->extension->getName());
    }

    public function testGetFunctions()
    {
        $this->assertContainsOnly('\Twig_SimpleFunction', $this->extension->getFunctions());
    }

    /**
     * Check the correct result for an existing option
     */
    public function testGetPostMeta()
    {
        $this->postMetaManager->expects($this->once())
            ->method('getPostMeta');

        $this->extension->getPostMeta(12, 'meta-test', true);
    }
}
