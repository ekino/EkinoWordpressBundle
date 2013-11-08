<?php

namespace Ekino\WordpressBundle\Tests\Listener;

use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Class WordpressRequestListenerTest
 *
 * This is the test class for the WordpressRequestListener that initializes the Wordpress application
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
        // Set up Wordpress instance mock
        $wordpress = $this->getWordpressMock();

        $wordpress->expects($this->any())
            ->method('getContent')
            ->will($this->returnValue('<html><body>My fake Wordpress content</body></html>'));

        // Set up a fake User to be returned by UserManager mocked below
        $userMock = $this->getMock('\Ekino\WordpressBundle\Entity\User', array('getMetaValue'));
        $userMock->expects($this->any())->method('getMetaValue')->will(
            $this->returnValue(serialize(array('administrator' => true)))
        );

        $userManager = $this->getUserManagerMock();
        $userManager->expects($this->any())->method('find')->will($this->returnValue($userMock));

        // Set up a security context mock for listener mock below
        $this->securityContext = $this->getSecurityContextMock();

        $this->listener = $this->getMock(
            '\Ekino\WordpressBundle\Listener\WordpressRequestListener',
            array('getWordpressLoggedIdentifier'),
            array($wordpress, $userManager, $this->securityContext)
        );
    }

    /**
     * Test onKernelResponse() method with no user logged in
     */
    public function testOnKernelRequestNotUserLogged()
    {
        $this->listener->expects($this->any())->method('getWordpressLoggedIdentifier')->will($this->returnValue(false));

        $this->assertNull($this->securityContext->getToken(), 'Should returns no token');
    }

    /**
     * Test onKernelResponse() & checkAuthentication() methods with a user logged in
     *
     * Should authenticate user and returns correct role
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
        $this->listener->onKernelRequest($getResponseEvent);

        $this->assertNotNull($this->securityContext->getToken(), 'Should returns no token');

        $this->assertEquals(1, $request->getSession()->get('wordpress_user_id'), 'Should return identifier stored in session');
        $this->assertEquals($this->securityContext->getToken(), $request->getSession()->get('token'), 'Should return token stored in session');

        $user = $this->securityContext->getToken()->getUser();

        $this->assertEquals(array('ROLE_WP_ADMINISTRATOR'), $user->getRoles());
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

    /**
     * Returns a mock of user manager
     *
     * @return \Ekino\WordpressBundle\Manager\UserManager
     */
    protected function getUserManagerMock()
    {
        return $this->getMockBuilder('\Ekino\WordpressBundle\Manager\UserManager')
            ->disableOriginalConstructor()
            ->getMock();
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