<?php

namespace Ekino\WordpressBundle\Listener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\SecurityContextInterface;

use Ekino\WordpressBundle\Manager\UserManager;
use Ekino\WordpressBundle\Wordpress\Wordpress;

/**
 * Class WordpressRequestListener
 *
 * This is a listener that loads Wordpress application on kernel request
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class WordpressRequestListener
{
    /**
     * @var Wordpress
     */
    protected $wordpress;

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
     * @param Wordpress                $wordpress       Wordpress service
     * @param UserManager              $userManager     Wordpress user manager
     * @param SecurityContextInterface $securityContext Symfony security context service
     */
    public function __construct(Wordpress $wordpress, UserManager $userManager, SecurityContextInterface $securityContext)
    {
        $this->wordpress       = $wordpress;
        $this->userManager     = $userManager;
        $this->securityContext = $securityContext;
    }

    /**
     * On kernel request method
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->wordpress->initialize();

        $request = $event->getRequest();
        $this->checkAuthentication($request);
    }

    /**
     * Checks if a Wordpress user is authenticated and authenticate him into Symfony security context
     *
     * @param Request $request
     */
    protected function checkAuthentication(Request $request)
    {
        $session = $request->getSession();

        $identifier = $this->getWordpressLoggedIdentifier($request);

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
            $user->setWordpressRoles($roles);

            $token = new UsernamePasswordToken($user, null, 'secured_area', $user->getRoles());
            $this->securityContext->setToken($token);

            $session->set('wordpress_user_id', $identifier);
            $session->set('token', $token);
        }
    }

    /**
     * Returns Wordpress loged in user identifier if logged otherwise returns false
     *
     * @param Request $request
     *
     * @return bool|int
     */
    protected function getWordpressLoggedIdentifier(Request $request)
    {
        return wp_validate_auth_cookie($request->cookies->get(LOGGED_IN_COOKIE), 'logged_in');
    }
}