<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle\Listener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\SecurityContextInterface;

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
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * Constructor
     *
     * @param SecurityContextInterface $securityContext Symfony security context service
     */
    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * On kernel request method
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
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

        if ($session->has('token')) {
            $token = $session->get('token');
            $this->securityContext->setToken($token);
        }
    }
}
