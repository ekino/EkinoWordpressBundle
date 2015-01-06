<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle\Event\Hook;

use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ekino\WordpressBundle\Event\WordpressEvent;
use Ekino\WordpressBundle\Manager\UserManager;

/**
 * Class UserHookListener
 *
 * This is a hook class that catch some user events fired by Wordpress
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class UserHookListener
{
    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var string
     */
    protected $firewall;

    /**
     * Constructor
     *
     * @param UserManager              $userManager     Wordpress bundle user manager
     * @param LoggerInterface          $logger          Symfony PSR logger
     * @param SecurityContextInterface $securityContext Symfony security context
     * @param SessionInterface         $session         Symfony session service
     * @param string                   $firewall        EkinoWordpressBundle firewall name
     */
    public function __construct(UserManager $userManager, LoggerInterface $logger, SecurityContextInterface $securityContext, SessionInterface $session, $firewall)
    {
        $this->userManager     = $userManager;
        $this->logger          = $logger;
        $this->securityContext = $securityContext;
        $this->session         = $session;
        $this->firewall        = $firewall;
    }

    /**
     * Wordpress user login hook method
     *
     * @param WordpressEvent $event
     *
     * @see http://codex.wordpress.org/Plugin_API/Action_Reference/wp_login
     */
    public function onLogin(WordpressEvent $event)
    {
        $wpUser = $event->getParameter('user');

        $user = $this->userManager->find($wpUser->data->ID);
        $user->setWordpressRoles($wpUser->roles);

        $token = new UsernamePasswordToken($user, $user->getPass(), $this->firewall, $user->getRoles());
        $this->securityContext->setToken($token);

        $this->session->set('_security_'.$this->firewall, serialize($token));
    }

    /**
     * Wordpress user log out hook method
     *
     * @param WordpressEvent $event
     *
     * @see http://codex.wordpress.org/Plugin_API/Action_Reference/wp_logout
     */
    public function onLogout(WordpressEvent $event)
    {
        $this->session->clear();
        $this->securityContext->setToken(null);
    }
}
