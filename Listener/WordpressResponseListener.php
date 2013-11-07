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

        $cookie = $request->cookies->get(sprintf('wordpress_logged_in_%s', md5(site_url())));

        // Checks if user is logged on Wordpress application
        if (null === $cookie) {
            $this->securityContext->setToken(null);
            $session->clear();

            return;
        }

        // Is not logged in Symfony security context, login (with session token if found)
        if (!$this->securityContext->getToken()) {
            $login = stristr($cookie, '|', true);

            if ($session->has('token') && $login == $session->get('wordpress_login')) {
                $token = $session->get('token');
                $this->securityContext->setToken($token);

                return;
            }

            $user = $this->userManager->findOneBy(array('login' => $login));

            $token = new UsernamePasswordToken($user, null, 'secured_area', $user->getRoles());
            $this->securityContext->setToken($token);

            $request->getSession()->set('wordpress_login', $user->getLogin());
            $request->getSession()->set('token', $token);
        }
    }
}