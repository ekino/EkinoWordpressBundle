<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle\Tests\Event\Hook;

use Ekino\WordpressBundle\Event\Hook\UserHookListener;

/**
 * Class UserHookListenerTest
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class UserHookListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Ekino\WordpressBundle\Manager\UserManager
     */
    protected $userManager;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Symfony\Component\Security\Core\SecurityContextInterface
     */
    protected $securityContext;

    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;

    /**
     * @var string
     */
    protected $firewall;

    /**
     * @var UserHookListener
     */
    protected $listener;

    /**
     * Sets up a UserHookListener instance
     */
    protected function setUp()
    {
        $this->userManager = $this->getMockBuilder('Ekino\WordpressBundle\Manager\UserManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->logger = $this->getMock('Psr\Log\LoggerInterface');

        $this->securityContext = $this->getMock('Symfony\Component\Security\Core\SecurityContextInterface');

        $this->session = $this->getMock('Symfony\Component\HttpFoundation\Session\SessionInterface');

        $this->firewall = 'secured_area';

        $this->listener = new UserHookListener($this->userManager, $this->logger, $this->securityContext, $this->session, $this->firewall);
    }

    /**
     * Tests onLogin() method
     */
    public function testOnLogin()
    {
        // Given
        $wpUserData = new \stdClass();
        $wpUserData->ID = 1;

        $wpUser = new \stdClass();
        $wpUser->data = $wpUserData;
        $wpUser->roles = array('administrator');

        $event = $this->getMock('Ekino\WordpressBundle\Event\WordpressEvent');
        $event->expects($this->once())->method('getParameter')->will($this->returnValue($wpUser));

        $user = $this->getMock('Ekino\WordpressBundle\Entity\User');
        $user->expects($this->once())->method('setWordpressRoles')->with($wpUser->roles);
        $user->expects($this->once())->method('getPass')->will($this->returnValue(1234));
        $user->expects($this->once())->method('getRoles')->will($this->returnValue(array('ROLE_WP_ADMINISTRATOR')));

        $this->userManager->expects($this->once())->method('find')->will($this->returnValue($user));

        $this->securityContext->expects($this->once())->method('setToken');

        // When - Then
        $this->listener->onLogin($event);
    }

    /**
     * Tests onLogout() method
     */
    public function testOnLogout()
    {
        // Given
        $event = $this->getMock('Ekino\WordpressBundle\Event\WordpressEvent');

        // When - Then
        $this->session->expects($this->once())->method('clear');
        $this->securityContext->expects($this->once())->method('setToken')->with(null);

        $this->listener->onLogout($event);
    }
}
