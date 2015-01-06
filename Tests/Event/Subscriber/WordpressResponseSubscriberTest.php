<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle\Tests\Event\Subscriber;

use Ekino\WordpressBundle\Event\Subscriber\WordpressResponseSubscriber;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class WordpressResponseSubscriberTest extends \PHPUnit_Framework_TestCase
{
    protected $event;
    protected $request;
    protected $response;
    protected $wordpress;

    /**
     * @var WordpressResponseSubscriber
     */
    protected $subscriber;

    protected function setUp()
    {
        $this->event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\FilterResponseEvent')->disableOriginalConstructor()->getMock();
        $this->response = $this->getMockBuilder('Ekino\WordpressBundle\Wordpress\WordpressResponse')->disableOriginalConstructor()->getMock();
        $this->request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->disableOriginalConstructor()->getMock();
        $this->wordpress = $this->getMockBuilder('Ekino\WordpressBundle\Wordpress\Wordpress')->disableOriginalConstructor()->getMock();

        $this->subscriber = new WordpressResponseSubscriber(array($this, 'getHeadersMock'), $this->wordpress);
    }

    public function testGetSubscribedEvents()
    {
        $expected = array(
            KernelEvents::RESPONSE => array('onKernelResponse'),
        );

        $this->assertEquals($expected, WordpressResponseSubscriber::getSubscribedEvents());
    }

    public function testGetHttpHeadersCallback()
    {
        $this->assertEquals(array($this, 'getHeadersMock'), $this->subscriber->getHttpHeadersCallback());
    }

    public function testOnKernelResponseNoWordpressResponse()
    {
        $event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\FilterResponseEvent')->disableOriginalConstructor()->getMock();
        $response = $this->getMockBuilder('Symfony\Component\HttpFoundation\Response')->disableOriginalConstructor()->getMock();

        $event->expects($this->once())
            ->method('getResponse')
            ->will($this->returnValue($response));

        $response->expects($this->never())
            ->method('getUri');

        $this->subscriber->onKernelResponse($event);
    }

    public function testOnKernelResponseNoMasterRequest()
    {
        $this->event->expects($this->once())
            ->method('getResponse')
            ->will($this->returnValue($this->response));
        $this->event->expects($this->once())
            ->method('getRequestType')
            ->will($this->returnValue(HttpKernelInterface::SUB_REQUEST));

        $this->response->expects($this->never())
            ->method('getUri');

        $this->subscriber->onKernelResponse($this->event);
    }

    public function testOnKernelResponseNoWpQuery()
    {
        $this->event->expects($this->once())
            ->method('getResponse')
            ->will($this->returnValue($this->response));
        $this->event->expects($this->once())
            ->method('getRequestType')
            ->will($this->returnValue(HttpKernelInterface::MASTER_REQUEST));
        $this->wordpress->expects($this->once())
            ->method('getWpQuery')
            ->will($this->returnValue(null));

        $this->request->expects($this->never())
            ->method('getUri');

        $this->subscriber->onKernelResponse($this->event);
    }

    public function testOnKernelResponseNo404()
    {
        $this->event->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($this->request));
        $this->event->expects($this->once())
            ->method('getResponse')
            ->will($this->returnValue($this->response));

        $this->event->expects($this->once())
            ->method('getRequestType')
            ->will($this->returnValue(HttpKernelInterface::MASTER_REQUEST));

        $this->wordpress->expects($this->once())
            ->method('getWpQuery')
            ->will($this->returnValue(new WP_QueryMock()));
        $this->request->expects($this->once())
            ->method('getUri')
            ->will($this->returnValue('http://wwww.test.com/random-test'));

        $this->subscriber->onKernelResponse($this->event);
    }

    public function testOnKernelResponsePushHeader()
    {
        $subscriber = new WordpressResponseSubscriber(array($this, 'getHavingHeadersMock'), $this->wordpress);

        $this->event->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($this->request));
        $this->event->expects($this->once())
            ->method('getResponse')
            ->will($this->returnValue($this->response));
        $this->event->expects($this->once())
            ->method('getRequestType')
            ->will($this->returnValue(HttpKernelInterface::MASTER_REQUEST));
        $this->wordpress->expects($this->once())
            ->method('getWpQuery')
            ->will($this->returnValue(new WP_QueryMock(true)));

        $parameterBag = $this->getMock('Symfony\Component\HttpFoundation\ParameterBag');
        $this->response->headers = $parameterBag;
        $parameterBag->expects($this->once())
            ->method('set')
            ->with($this->equalTo('test-header'), $this->equalTo('is working'));

        $this->request->expects($this->once())
            ->method('getUri')
            ->will($this->returnValue('http://wwww.test.com/random-test'));
        $this->response->expects($this->once())
            ->method('setStatusCode')
            ->with($this->equalTo(404));

        $subscriber->onKernelResponse($this->event);
    }

    public function testOnKernelResponseNoPushCacheHeader()
    {
        $subscriber = new WordpressResponseSubscriber(array($this, 'getHavingHeadersCacheMock'), $this->wordpress);

        $this->event->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($this->request));
        $this->event->expects($this->once())
            ->method('getResponse')
            ->will($this->returnValue($this->response));
        $this->event->expects($this->once())
            ->method('getRequestType')
            ->will($this->returnValue(HttpKernelInterface::MASTER_REQUEST));
        $this->wordpress->expects($this->once())
            ->method('getWpQuery')
            ->will($this->returnValue(new WP_QueryMock(true)));

        $parameterBag = $this->getMock('Symfony\Component\HttpFoundation\ParameterBag');
        $this->response->headers = $parameterBag;
        $parameterBag->expects($this->never())
            ->method('set');

        $this->request->expects($this->once())
            ->method('getUri')
            ->will($this->returnValue('http://wwww.test.com/random-test'));
        $this->response->expects($this->once())
            ->method('setStatusCode')
            ->with($this->equalTo(404));

        $subscriber->onKernelResponse($this->event);
    }

    public function testOnKernelResponse()
    {
        $this->event->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($this->request));
        $this->event->expects($this->once())
            ->method('getResponse')
            ->will($this->returnValue($this->response));
        $this->event->expects($this->once())
            ->method('getRequestType')
            ->will($this->returnValue(HttpKernelInterface::MASTER_REQUEST));
        $this->wordpress->expects($this->once())
            ->method('getWpQuery')
            ->will($this->returnValue(new WP_QueryMock(true)));

        $this->request->expects($this->once())
            ->method('getUri')
            ->will($this->returnValue('http://wwww.test.com/random-test'));
        $this->response->expects($this->once())
            ->method('setStatusCode')
            ->with($this->equalTo(404));

        $this->subscriber->onKernelResponse($this->event);
    }

    /**
     * @param string $uri
     *
     * @return array
     */
    public function getHeadersMock($uri)
    {
        return array();
    }

    /**
     * @param string $uri
     *
     * @return array
     */
    public function getHavingHeadersMock($uri)
    {
        return array(
            'test-header' => 'is working',
        );
    }

    /**
     * @param string $uri
     *
     * @return array
     */
    public function getHavingHeadersCacheMock($uri)
    {
        return array(
            'cache-control' => 'should not work',
        );
    }
}

class WP_QueryMock
{
    /**
     * @var bool
     */
    protected $is404;

    public function __construct($is404 = false)
    {
        $this->is404 = $is404;
    }

    public function is_404()
    {
        return $this->is404;
    }
}
