<?php

namespace Ekino\WordpressBundle\Listener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\SecurityContextInterface;

use Ekino\WordpressBundle\Manager\UserManager;
use Ekino\WordpressBundle\Wordpress\WordpressResponse;

/**
 * Class WordpressResponseListener
 *
 * This is a listener that interacts with WordpressResponse response types
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class WordpressResponseListener
{
    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * Constructor
     *
     * @param SecurityContextInterface $securityContext Symfony security context service
     * @param UserManager              $userManager     Wordpress user manager
     */
    public function __construct(SecurityContextInterface $securityContext, UserManager $userManager)
    {
        $this->securityContext = $securityContext;
        $this->userManager     = $userManager;
    }

    /**
     * On kernel response method
     *
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request  = $event->getRequest();
        $response = $event->getResponse();

        if ($response instanceof WordpressResponse) {
            $this->checkAuthentication($request);
        }
    }

    /**
     * Checks if a Wordpress user is authenticated and authenticate him into Symfony security context
     *
     * @param Request $request
     */
    protected function checkAuthentication(Request $request)
    {
        $session = $request->getSession();

        $identifier = wp_validate_auth_cookie($request->cookies->get(LOGGED_IN_COOKIE), 'logged_in');

        // If no user logged in Wordpress, logout user into Symfony
        if (false === $identifier) {
            $this->securityContext->setToken(null);
            $session->clear();

            return;
        }

        // Is not logged into Symfony security context, login (with session token if found)
        if (!$this->securityContext->getToken()) {
            if ($session->has('token') && $identifier == $session->get('wordpress_user_id')) {
                $token = $session->get('token');
                $this->securityContext->setToken($token);

                return;
            }

            // Find user and sets Wordpress role as Symfony role
            $user = $this->userManager->find($identifier);

            $capabilities = $user->getMetaValue('wp_capabilities');
            $roles = array_keys(unserialize($capabilities));
            $user->setRoles($roles);

            $token = new UsernamePasswordToken($user, null, 'secured_area', $user->getRoles());
            $this->securityContext->setToken($token);

            $session->set('wordpress_user_id', $identifier);
            $session->set('token', $token);
        }
    }
}