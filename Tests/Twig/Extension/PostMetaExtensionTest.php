<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace {
    function wp_get_attachment_url($imgId)
    {
        return 'http://www.exemple.com/image.jpg';
    }
}

namespace Ekino\WordpressBundle\Tests\Twig\Extension {

    use Ekino\WordpressBundle\Twig\Extension\PostMetaExtension;

    /**
     * Class PostMetaExtensionTest.
     *
     * @author Xavier Coureau <xav.is@2cool4school.fr>
     */
    class PostMetaExtensionTest extends \PHPUnit\Framework\TestCase
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
            if (!class_exists('\Twig\Extension\AbstractExtension')) {
                $this->markTestSkipped('Twig is not enabled');
            }

            $this->postMetaManager = $this->getMockBuilder('Ekino\WordpressBundle\Manager\PostMetaManager')->disableOriginalConstructor()->getMock();
            $this->extension = new PostMetaExtension($this->postMetaManager);
        }

        /**
         *   Get test name.
         */
        public function testGetName()
        {
            $this->assertEquals('ekino_wordpress_post_meta', $this->extension->getName());
        }

        /**
         *   Get test functions.
         */
        public function testGetFunctions()
        {
            $this->assertContainsOnly('\Twig\TwigFunction', $this->extension->getFunctions());
        }

        /**
         * Check the correct result for an existing option.
         */
        public function testGetPostMeta()
        {
            $postMeta = $this->getMockBuilder('Ekino\WordpressBundle\Entity\PostMeta')->getMock();
            $this->postMetaManager->expects($this->once())
                ->method('getPostMeta')
                ->with($this->equalTo(12), $this->equalTo('meta-test'), $this->equalTo(true))
                ->will($this->returnValue($postMeta));

            $result = $this->extension->getPostMeta(12, 'meta-test', true);
            $this->assertEquals($postMeta, $result);
        }

        /**
         *  Check getImageUrlFromId method.
         */
        public function testGetImageUrlFromId()
        {
            $this->assertEquals('http://www.exemple.com/image.jpg', $this->extension->getImageUrlFromId(1));
        }
    }
}
