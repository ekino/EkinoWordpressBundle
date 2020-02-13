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

use Ekino\WordpressBundle\Twig\Extension\OptionExtension;

/**
 * Class OptionExtensionTest.
 *
 * @author Xavier Coureau <xav.is@2cool4school.fr>
 */
class OptionExtensionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $optionManager;

    /**
     * @var OptionExtension
     */
    protected $extension;

    protected function setUp()
    {
        if (!class_exists('\Twig\Extension\AbstractExtension')) {
            $this->markTestSkipped('Twig is not enabled');
        }

        $this->optionManager = $this->getMockBuilder('Ekino\WordpressBundle\Manager\OptionManager')->disableOriginalConstructor()->getMock();
        $this->extension = new OptionExtension($this->optionManager);
    }

    public function testGetName()
    {
        $this->assertEquals('ekino_wordpress_option', $this->extension->getName());
    }

    public function testGetFunctions()
    {
        $this->assertContainsOnly('\Twig\TwigFunction', $this->extension->getFunctions());
    }

    /**
     * Check the correct result for an existing option.
     */
    public function testGetOption()
    {
        $optionMock = $this->getMockBuilder('Ekino\WordpressBundle\Entity\Option')->getMock();

        $this->optionManager->expects($this->once())
            ->method('findOneByOptionName')
            ->with($this->equalTo('test'))
            ->will($this->returnValue($optionMock));

        $result = $this->extension->getOption('test');
        $this->assertEquals($optionMock, $result);
    }

    /**
     * Check the usage of default return value for a non existing option.
     */
    public function testGetOptionUndefined()
    {
        $this->optionManager->expects($this->once())
            ->method('findOneByOptionName')
            ->with($this->equalTo('test'))
            ->will($this->returnValue(null));

        $result = $this->extension->getOption('test', 'poney');
        $this->assertEquals('poney', $result);
    }
}
