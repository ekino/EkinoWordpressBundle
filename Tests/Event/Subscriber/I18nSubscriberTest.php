<?php

namespace Ekino\WordpressBundle\Tests\Event\Subscriber\I18n;

use Ekino\WordpressBundle\Event\Subscriber\I18n\I18nSubscriber;

class RequestSubscriberTest extends \PHPUnit_Framework_TestCase
{
    protected $defaultLanguage;
    protected $cookieName;

    /**
     * @var I18nSubscriber
     */
    protected $subscriber;

    protected function setUp()
    {
        $this->defaultLanguage = 'fr';
        $this->cookieName = 'my_wp_i18n_cookie';

        $this->subscriber = new I18nSubscriber($this->defaultLanguage, $this->cookieName);
    }

    public function testGetSubscribedEvents()
    {
        $this->assertInternalType('array', I18nSubscriber::getSubscribedEvents());
    }

    public function testOnKernelRequestNoExisingCookie()
    {
        $event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\GetResponseEvent')->disableOriginalConstructor()->getMock();
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->disableOriginalConstructor()->getMock();
        $cookies = $this->getMockBuilder('Symfony\Component\HttpFoundation\ParameterBag')->disableOriginalConstructor()->getMock();

        $request->cookies = $cookies;

        $event->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($request));

        $cookies->expects($this->once())
            ->method('get')
            ->with($this->equalTo($this->cookieName), $this->equalTo($this->defaultLanguage))
            ->will($this->returnValue($this->defaultLanguage));

        $request->expects($this->once())
            ->method('setLocale')
            ->with($this->equalTo($this->defaultLanguage));

        $this->subscriber->onKernelRequest($event);
    }

    public function testOnKernelRequest()
    {
        $event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\GetResponseEvent')->disableOriginalConstructor()->getMock();
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->disableOriginalConstructor()->getMock();
        $cookies = $this->getMockBuilder('Symfony\Component\HttpFoundation\ParameterBag')->disableOriginalConstructor()->getMock();

        $request->cookies = $cookies;

        $event->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($request));

        $cookies->expects($this->once())
            ->method('get')
            ->with($this->equalTo($this->cookieName), $this->equalTo($this->defaultLanguage))
            ->will($this->returnValue('en'));

        $request->expects($this->once())
            ->method('setLocale')
            ->with($this->equalTo('en'));

        $this->subscriber->onKernelRequest($event);
    }
}