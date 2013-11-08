<?php

namespace Ekino\WordpressBundle\Tests\Entity;

use Ekino\WordpressBundle\Entity\User;

/**
 * Class UserTest
 *
 * This is the Wordpress user entity test
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test setting classic Symfony roles
     */
    public function testSymfonyRoles()
    {
        $roles = array('ROLE_ADMINISTRATOR');

        $user = new User();
        $user->setRoles($roles);

        $this->assertEquals(array('ROLE_ADMINISTRATOR'), $user->getRoles());
    }

    /**
     * Test setting Wordpress roles and should return Symfony roles
     */
    public function testWordpressRoles()
    {
        $wordpressRoles = array('author');

        $user = new User();
        $user->setWordpressRoles($wordpressRoles);

        $this->assertEquals(array('ROLE_WP_AUTHOR'), $user->getRoles());
    }
}