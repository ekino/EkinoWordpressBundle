<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle\Tests\Listener;

use Ekino\WordpressBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class WordpressRequestListenerTest.
 *
 * This is the test class for the WordpressRequestListener that initializes the Wordpress application
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class WordpressRequestListenerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Ekino\WordpressBundle\Listener\WordpressRequestListener
     */
    protected $listener;

    /**
     * @var \Ekino\WordpressBundle\Wordpress\Wordpress
     */
    protected $wordpress;

    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage
     */
    protected $tokenStorage;

    /**
     * Set up required mocks for WordpressRequestListener class.
     */
    protected function setUp()
    {
        // Set up a fake User to be returned by UserManager mocked below
        $userMock = $this->getMockBuilder('\Ekino\WordpressBundle\Entity\User')
            ->setMethods(['getMetaValue'])
            ->getMock();
        $userMock->expects($this->any())->method('getMetaValue')->will(
            $this->returnValue(serialize(['administrator' => true]))
        );

        $this->wordpress = $this->getMockBuilder('\Ekino\WordpressBundle\Wordpress\Wordpress')
            ->disableOriginalConstructor()
            ->getMock();

        $this->tokenStorage = new TokenStorage();

        $this->listener = $this->getMockBuilder('\Ekino\WordpressBundle\Listener\WordpressRequestListener')
            ->setMethods(['getWordpressLoggedIdentifier'])
            ->setConstructorArgs([$this->wordpress, $this->tokenStorage])
            ->getMock();
    }

    /**
     * Test onKernelResponse() & checkAuthentication() methods with a user logged in
     * and route is a Symfony route (Wordpress code must be loaded).
     *
     * Should sets user token in security context if session key 'token' exists
     */
    public function testOnKernelRequestUserLoggedAndSymfonyRoute()
    {
        // Fake Wordpress application to return a user logged in identifier
        $this->listener
            ->expects($this->any())
            ->method('getWordpressLoggedIdentifier')
            ->will($this->returnValue(1));

        $user = new User();
        $token = new UsernamePasswordToken($user, $user->getPass(), 'secured_area', $user->getRoles());

        // Set up a request mock to give to GetResponseEvent class below
        $session = $this->getMockBuilder('\Symfony\Component\HttpFoundation\Session\Session')->getMock();
        $session->expects($this->any())->method('getName')->will($this->returnValue('session_test'));
        $session->expects($this->any())->method('has')->with($this->equalTo('token'))->will($this->returnValue(true));
        $session->expects($this->any())->method('get')->with($this->equalTo('token'))->will($this->returnValue($token));

        $request = new Request();
        $request->setSession($session);
        $request->attributes->set('_route', 'symfony_test_route');
        $request->cookies->set($request->getSession()->getName(), true);

        // Ensure Wordpress is loaded as route is not the catch all route.
        $this->wordpress->expects($this->once())->method('loadWordpress');

        $httpKernelMock = $this->getMockBuilder('\Symfony\Component\HttpKernel\HttpKernelInterface')->getMock();

        $getResponseEvent = new GetResponseEvent(
            $httpKernelMock, $request, HttpKernelInterface::MASTER_REQUEST
        );

        // Run onKernelRequest() method
        $this->assertEquals(null, $this->tokenStorage->getToken(), 'Should returns no token');

        $this->listener->onKernelRequest($getResponseEvent);

        $this->assertEquals($token, $this->tokenStorage->getToken(), 'Should returns previous token initialized');
    }

    /**
     * Test onKernelResponse() & checkAuthentication() methods with a user logged in
     * and Wordpress catchall route is loaded (Wordpress should not be loaded).
     *
     * Should sets user token in security context if session key 'token' exists
     */
    public function testOnKernelRequestUserLoggedAndCatchallRoute()
    {
        // Fake Wordpress application to return a user logged in identifier
        $this->listener
            ->expects($this->any())
            ->method('getWordpressLoggedIdentifier')
            ->will($this->returnValue(1));

        $user = new User();
        $token = new UsernamePasswordToken($user, $user->getPass(), 'secured_area', $user->getRoles());

        // Set up a request mock to give to GetResponseEvent class below
        $session = $this->getMockBuilder('\Symfony\Component\HttpFoundation\Session\Session')->getMock();
        $session->expects($this->any())->method('getName')->will($this->returnValue('session_test'));
        $session->expects($this->any())->method('has')->with($this->equalTo('token'))->will($this->returnValue(true));
        $session->expects($this->any())->method('get')->with($this->equalTo('token'))->will($this->returnValue($token));

        $request = new Request();
        $request->setSession($session);
        $request->attributes->set('_route', 'ekino_wordpress_catchall');
        $request->cookies->set($request->getSession()->getName(), true);

        // Ensure Wordpress is loaded as route is not the catch all route.
        $this->wordpress->expects($this->never())->method('loadWordpress');

        $httpKernelMock = $this->getMockBuilder('\Symfony\Component\HttpKernel\HttpKernelInterface')->getMock();

        $getResponseEvent = new GetResponseEvent(
            $httpKernelMock, $request, HttpKernelInterface::MASTER_REQUEST
        );

        // Run onKernelRequest() method
        $this->assertEquals(null, $this->tokenStorage->getToken(), 'Should returns no token');

        $this->listener->onKernelRequest($getResponseEvent);

        $this->assertEquals($token, $this->tokenStorage->getToken(), 'Should returns previous token initialized');
    }

    /**
     * Returns a Symfony session service.
     *
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    protected function getSession()
    {
        return new Session(new MockArraySessionStorage(), new AttributeBag(), new FlashBag());
    }
}
