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

use Ekino\WordpressBundle\Twig\Extension\PostExtension;
use PHPUnit\Framework\TestCase;

class PostExtensionTest extends TestCase
{
    protected $postManager;
    protected $optionExtension;

    /**
     * @var PostExtension
     */
    protected $postExtension;

    protected function setUp()
    {
        if (!class_exists('\Twig\Extension\AbstractExtension')) {
            $this->markTestSkipped('Twig is not enabled');
        }

        $this->postManager = $this->getMockBuilder('Ekino\WordpressBundle\Manager\PostManager')->disableOriginalConstructor()->getMock();
        $this->optionExtension = $this->getMockBuilder('Ekino\WordpressBundle\Twig\Extension\OptionExtension')->disableOriginalConstructor()->getMock();

        $this->postExtension = new PostExtension($this->postManager, $this->optionExtension);
    }

    public function testGetName()
    {
        $this->assertEquals('ekino_wordpress_post', $this->postExtension->getName());
    }

    public function testGetFunctions()
    {
        $this->assertContainsOnly('\Twig\TwigFunction', $this->postExtension->getFunctions());
    }

    public function testReplacePostArguments()
    {
        $permalinkStructure = '/%year%/%monthnum%/%day%/%post_id%-%postname%';
        $post = $this->getMockBuilder('Ekino\WordpressBundle\Entity\Post')->getMock();
        $post->expects($this->once())
            ->method('getDate')
            ->will($this->returnValue(new \DateTime()));
        $post->expects($this->once())
            ->method('getId')
            ->will($this->returnValue('12'));
        $post->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('sample-post'));

        $result = $this->postExtension->replacePostArguments($permalinkStructure, $post);

        $this->assertEquals(date('/Y/m/d').'/12-sample-post', $result);
    }

    public function testGetPermalinkNoPost()
    {
        $this->postManager->expects($this->once())
            ->method('find')
            ->will($this->returnValue(false));

        $this->expectException(\UnexpectedValueException::class);
        $this->postExtension->getPermalink(12);
    }

    public function testGetPermalink()
    {
        $post = $this->getMockBuilder('Ekino\WordpressBundle\Entity\Post')->getMock();
        $permalinkOption = $this->getMockBuilder('Ekino\WordpressBundle\Entity\Option')->getMock();

        $post->expects($this->once())
            ->method('getDate')
            ->will($this->returnValue(new \DateTime()));
        $post->expects($this->once())
            ->method('getId')
            ->will($this->returnValue('12'));
        $post->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('sample-post'));

        $permalinkOption->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue('/%year%/%monthnum%/%day%/%post_id%-%postname%'));
        $this->postManager->expects($this->once())
            ->method('find')
            ->will($this->returnValue($post));
        $this->optionExtension->expects($this->once())
            ->method('getOption')
            ->will($this->returnValue($permalinkOption));

        $result = $this->postExtension->getPermalink(12);
        $this->assertEquals(date('/Y/m/d').'/12-sample-post', $result);
    }

    public function testGetAbsolutePermalink()
    {
        $post = $this->getMockBuilder('Ekino\WordpressBundle\Entity\Post')->getMock();
        $permalinkOption = $this->getMockBuilder('Ekino\WordpressBundle\Entity\Option')->getMock();
        $homeOption = $this->getMockBuilder('Ekino\WordpressBundle\Entity\Option')->getMock();

        $post->expects($this->once())
            ->method('getDate')
            ->will($this->returnValue(new \DateTime()));
        $post->expects($this->once())
            ->method('getId')
            ->will($this->returnValue('12'));
        $post->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('sample-post'));

        $permalinkOption->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue('/%year%/%monthnum%/%day%/%post_id%-%postname%'));
        $homeOption->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue('http://localhost/blog/'));
        $this->postManager->expects($this->once())
            ->method('find')
            ->will($this->returnValue($post));
        $this->optionExtension->expects($this->at(0))
            ->method('getOption')
            ->will($this->returnValue($permalinkOption));
        $this->optionExtension->expects($this->at(1))
            ->method('getOption')
            ->will($this->returnValue($homeOption));

        $result = $this->postExtension->getPermalink(12, true);
        $this->assertEquals('http://localhost/blog'.date('/Y/m/d').'/12-sample-post', $result);
    }
}
