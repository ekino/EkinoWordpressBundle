<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle\Tests\Event\Subscriber\I18n;

use Ekino\WordpressBundle\Event\Subscriber\I18n\I18nSubscriber;

/**
 * Class RequestSubscriberTest.
 */
class I18nSubscriberTest extends \PHPUnit\Framework\TestCase
{
    protected $defaultLanguage;
    protected $cookieName;

    /**
     * @var I18nSubscriber
     */
    protected $subscriber;

    /**
     * Sets up a I18nSubscriber instance.
     */
    protected function setUp()
    {
        $this->defaultLanguage = 'fr';
        $this->cookieName = 'my_wp_i18n_cookie';

        $this->subscriber = new I18nSubscriber($this->defaultLanguage, $this->cookieName);
    }

    /**
     * Tests static getSubscribedEvents() method.
     */
    public function testGetSubscribedEvents()
    {
        $this->assertInternalType('array', I18nSubscriber::getSubscribedEvents());
    }

    /**
     * Tests onKernelRequest() method when no cookies are existing.
     */
    public function testOnKernelRequestNoExisingCookie()
    {
        $event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\GetResponseEvent')->disableOriginalConstructor()->getMock();
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->disableOriginalConstructor()->getMock();
        $cookies = $this->getMockBuilder('Symfony\Component\HttpFoundation\ParameterBag')->disableOriginalConstructor()->getMock();
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')->disableOriginalConstructor()->getMock();

        $request->cookies = $cookies;

        $session->expects($this->once())
            ->method('get')
            ->with($this->equalTo('_locale'))
            ->will($this->returnValue($this->defaultLanguage));

        $request->expects($this->once())
            ->method('getSession')
            ->will($this->returnValue($session));
        $event->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($request));

        $cookies->expects($this->once())
            ->method('get')
            ->with($this->equalTo($this->cookieName), $this->equalTo($this->defaultLanguage))
            ->will($this->returnValue($this->defaultLanguage));

        $session->expects($this->once())
            ->method('set')
            ->with($this->equalTo('_locale'), $this->equalTo($this->defaultLanguage));
        $request->expects($this->once())
            ->method('setLocale')
            ->with($this->equalTo($this->defaultLanguage));

        $this->subscriber->onKernelRequest($event);
    }

    /**
     * Tests onKernelRequest() method.
     */
    public function testOnKernelRequest()
    {
        $event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\GetResponseEvent')->disableOriginalConstructor()->getMock();
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->disableOriginalConstructor()->getMock();
        $cookies = $this->getMockBuilder('Symfony\Component\HttpFoundation\ParameterBag')->disableOriginalConstructor()->getMock();
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')->disableOriginalConstructor()->getMock();

        $request->cookies = $cookies;

        $request->expects($this->once())
            ->method('getSession')
            ->will($this->returnValue($session));
        $event->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($request));

        $cookies->expects($this->once())
            ->method('get')
            ->will($this->returnValue('en'));

        $session->expects($this->once())
            ->method('set')
            ->with($this->equalTo('_locale'), $this->equalTo('en'));
        $request->expects($this->once())
            ->method('setLocale')
            ->with($this->equalTo('en'));

        $this->subscriber->onKernelRequest($event);
    }
}
