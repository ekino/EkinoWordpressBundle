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
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Class WordpressRequestListenerTest
 *
 * This is the test class for the WordpressRequestListener that initializes the Wordpress application
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class WordpressRequestListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Ekino\WordpressBundle\Listener\WordpressRequestListener
     */
    protected $listener;

    /**
     * @var \Symfony\Component\Security\Core\SecurityContext
     */
    protected $securityContext;

    /**
     * Set up required mocks for WordpressRequestListener class
     */
    protected function setUp()
    {
        // Set up a fake User to be returned by UserManager mocked below
        $userMock = $this->getMock('\Ekino\WordpressBundle\Entity\User', array('getMetaValue'));
        $userMock->expects($this->any())->method('getMetaValue')->will(
            $this->returnValue(serialize(array('administrator' => true)))
        );

        // Set up a security context mock for listener mock below
        $this->securityContext = $this->getSecurityContextMock();

        $this->listener = $this->getMock(
            '\Ekino\WordpressBundle\Listener\WordpressRequestListener',
            array('getWordpressLoggedIdentifier'),
            array($this->securityContext)
        );
    }

    /**
     * Test onKernelResponse() & checkAuthentication() methods with a user logged in
     *
     * Should sets user token in security context if session key 'token' exists
     */
    public function testOnKernelRequestUserLogged()
    {
        // Fake Wordpress application to return a user logged in identifier
        $this->listener
            ->expects($this->any())
            ->method('getWordpressLoggedIdentifier')
            ->will($this->returnValue(1));

        // Set up a request mock to give to GetResponseEvent class below
        $request = $this->getMock('\Symfony\Component\HttpFoundation\Request');
        $request->expects($this->any())->method('getSession')->will($this->returnValue($this->getSession()));

        $getResponseEvent = new GetResponseEvent(
            $this->getMock('\Symfony\Component\HttpKernel\HttpKernelInterface'), $request, HttpKernelInterface::MASTER_REQUEST
        );

        // Run onKernelRequest() method
        $this->assertEquals(null, $this->securityContext->getToken(), 'Should returns no token');

        $user = new User();
        $token = new UsernamePasswordToken($user, $user->getPass(), 'secured_area', $user->getRoles());
        $getResponseEvent->getRequest()->getSession()->set('token', $token);
        $this->listener->onKernelRequest($getResponseEvent);

        $this->assertEquals($token, $this->securityContext->getToken(), 'Should returns previous token initialized');
    }

    /**
     * Returns a mock of Symfony security context service
     *
     * @return \Symfony\Component\Security\Core\SecurityContext
     */
    protected function getSecurityContextMock()
    {
        $authenticationManagerMock = $this->getMockBuilder('\Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $accessDecisionManagerMock = $this->getMockBuilder('\Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        return new SecurityContext($authenticationManagerMock, $accessDecisionManagerMock);
    }

    /**
     * Returns a Symfony session service
     *
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    protected function getSession()
    {
        return new Session(new MockArraySessionStorage(), new AttributeBag(), new FlashBag());
    }
}
