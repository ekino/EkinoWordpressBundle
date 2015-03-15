<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle\Security;

/**
 * Class WordPressEntryPoint
 *
 * This class can be used in the firewall configuration to redirect
 * a user not authenticated or with insufficient permissions.
 *
 * @author Jérôme Tamarelle <jerome@tamarelle.net>
 */
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class WordpressEntryPoint
 *
 * This is a Symfony security component entry point to manage Wordpress login.
 */
class WordpressEntryPoint implements AccessDeniedHandlerInterface, AuthenticationEntryPointInterface
{
    /**
     * @var string URL to wp-login.php
     */
    private $loginUrl;

    /**
     * @param string $loginUrl URL to wp-login.php
     */
    public function __construct($loginUrl = '/wp-login.php')
    {
        $this->loginUrl = $loginUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return $this->createRedirectResponse($request);
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        return $this->createRedirectResponse($request);
    }

    private function createRedirectResponse($request)
    {
        $url = $this->loginUrl.'?'.http_build_query(array(
            'redirect_to' => $request->getUri(),
            'reauth' => 0,
        ));

        return RedirectResponse::create($url, 302);
    }
}
