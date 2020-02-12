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

class WordpressResponseSubscriberTest extends \PHPUnit\Framework\TestCase
{
    protected $event;
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
        $this->wordpress = $this->getMockBuilder('Ekino\WordpressBundle\Wordpress\Wordpress')->disableOriginalConstructor()->getMock();

        $this->subscriber = new WordpressResponseSubscriber($this->wordpress);
    }

    public function testGetSubscribedEvents()
    {
        $expected = [
            KernelEvents::RESPONSE => ['onKernelResponse'],
        ];

        $this->assertEquals($expected, WordpressResponseSubscriber::getSubscribedEvents());
    }

    public function testOnKernelResponseNoWordpressResponse()
    {
        $event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\FilterResponseEvent')->disableOriginalConstructor()->getMock();
        $response = $this->getMockBuilder('Symfony\Component\HttpFoundation\Response')->disableOriginalConstructor()->getMock();

        $event->expects($this->once())
            ->method('getResponse')
            ->will($this->returnValue($response));

        $response->expects($this->never())->method('setStatusCode');

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

        $this->response->expects($this->never())->method('setStatusCode');

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

        $this->subscriber->onKernelResponse($this->event);
    }

    public function testOnKernelResponse()
    {
        $this->event->expects($this->once())
            ->method('getResponse')
            ->will($this->returnValue($this->response));
        $this->event->expects($this->once())
            ->method('getRequestType')
            ->will($this->returnValue(HttpKernelInterface::MASTER_REQUEST));
        $this->wordpress->expects($this->once())
            ->method('getWpQuery')
            ->will($this->returnValue(new WP_QueryMock(true)));

        $this->response->expects($this->once())
            ->method('setStatusCode')
            ->with($this->equalTo(404));

        $this->subscriber->onKernelResponse($this->event);
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
