<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle\Tests\Controller;

/**
 * Class WordpressControllerTest
 *
 * This is the Wordpress bundle controller test
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class WordpressControllerTest extends \PHPUnit_Framework_TestCase
{
    protected $wp_query;

    /**
     * @var \Ekino\WordpressBundle\Wordpress\Wordpress
     */
    protected $wordpress;

    /**
     * Set up required mocks for Wordpress controller class
     */
    protected function setUp()
    {
        // Set up Wordpress instance mock
        $this->wordpress = $this->getWordpressMock();

        $this->wordpress->expects($this->any())
            ->method('getContent')
            ->will($this->returnValue('<html><body>My fake Wordpress content</body></html>'));

        $this->wordpress->initialize();
    }

    /**
     * Test catchAllAction() method
     */
    public function testCatchAllAction()
    {
        $controller = $this->getMock('\Ekino\WordpressBundle\Controller\WordpressController', array('getWordpress'));
        $controller->expects($this->any())->method('getWordpress')->will($this->returnValue($this->wordpress));

        $response = $controller->catchAllAction();

        $this->assertInstanceOf('\Ekino\WordpressBundle\Wordpress\WordpressResponse', $response, 'Should returns a WordpressResponse instance');
    }

    /**
     * Returns a mock of Wordpress class
     *
     * @return \Ekino\WordpressBundle\Wordpress\Wordpress
     */
    protected function getWordpressMock()
    {
        $kernel = $this->getKernelMock();

        return $this->getMock('\Ekino\WordpressBundle\Wordpress\Wordpress', array('getContent'), array($kernel));
    }

    /**
     * Returns a mock of Symfony kernel
     *
     * @return \Symfony\Component\HttpKernel\Kernel
     */
    protected function getKernelMock()
    {
        return $this->getMockBuilder('\Symfony\Component\HttpKernel\Kernel')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
