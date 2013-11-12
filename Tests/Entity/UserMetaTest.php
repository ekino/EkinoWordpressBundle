<?php

namespace Ekino\WordpressBundle\Tests\Entity;

use Ekino\WordpressBundle\Entity\User;
use Ekino\WordpressBundle\Entity\UserMeta;

/**
 * Class UserMetaTest
 *
 * This is the Wordpress user meta entity test
 */
class UserMetaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test entity getters & setters
     */
    public function testGettersSetters()
    {
        $entity = new UserMeta();

        $user = new User();
        $user->setDisplayName('display name');
        $entity->setUser($user);

        $entity->setKey('fake key');
        $entity->setValue('fake value');

        $this->assertEquals('display name', $entity->getUser()->getDisplayName());
        $this->assertEquals('fake key', $entity->getKey());
        $this->assertEquals('fake value', $entity->getValue());
    }
}