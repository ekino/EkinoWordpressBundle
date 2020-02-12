<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle\Tests\Security;

use Ekino\WordpressBundle\Security\WordpressEntryPoint;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class WordpressEntryPointTest.
 *
 * This is the test class for the WordpressEntryPoint class
 *
 * @author Jérôme Tamarelle <jerome@tamarelle.net>
 */
class WordPressEntryPointTest extends \PHPUnit\Framework\TestCase
{
    public function testEntryPoint()
    {
        $entryPoint = new WordpressEntryPoint();

        $request = Request::create('/private');

        $response = $entryPoint->start($request, new AuthenticationException());

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/wp-login.php?redirect_to=http%3A%2F%2Flocalhost%2Fprivate&reauth=0', $response->getTargetUrl());
    }

    public function testAccessDeniedHandler()
    {
        $entryPoint = new WordpressEntryPoint('/wordpress/wp-login.php');

        $request = Request::create('/denied');

        $response = $entryPoint->handle($request, new AccessDeniedException());

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/wordpress/wp-login.php?redirect_to=http%3A%2F%2Flocalhost%2Fdenied&reauth=0', $response->getTargetUrl());
    }
}
