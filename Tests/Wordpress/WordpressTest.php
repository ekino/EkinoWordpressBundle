<?php

namespace Ekino\WordpressBundle\Tests\Wordpress;

/**
 * Class WordpressTest
 *
 * This is the test class for the Wordpress class
 */
class WordpressTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Ekino\WordpressBundle\Wordpress\Wordpress
     */
    protected $wordpress;

    /**
     * This is the fake content returned by Wordpress class
     *
     * @var string
     */
    protected $content;

    /**
     * Set up Wordpress class
     */
    protected function setUp()
    {
        $this->wordpress = $this->getWordpressMock();
        $this->content = '<html><body>My fake Wordpress content</body></html>';

        $this->wordpress
            ->expects($this->any())
            ->method('getContent')
            ->will($this->returnValue($this->content));
    }

    /**
     * Tests Wordpress initialize() method
     *
     * Should return content of Wordpress
     */
    public function testInitialize()
    {
        $this->wordpress->initialize();

        $this->assertEquals($this->content, $this->wordpress->getContent(), 'Wordpress content should be returned after initialize()');
    }

    /**
     * Tests Wordpress getResponse() method
     *
     * Should return a WordpressResponse instance and fake content initialized
     */
    public function testGetResponse()
    {
        $this->wordpress->initialize();

        $response = $this->wordpress->getResponse();

        $this->assertInstanceOf('\Ekino\WordpressBundle\Wordpress\WordpressResponse', $response, 'Should return a WordpressResponse instance');
        $this->assertEquals($this->content, $response->getContent(), 'Wordpress content should be returned');
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