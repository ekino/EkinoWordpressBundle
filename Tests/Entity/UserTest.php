<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ekino\WordpressBundle\Entity\User;
use Ekino\WordpressBundle\Entity\UserMeta;

/**
 * Class UserTest.
 *
 * This is the Wordpress user entity test
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class UserTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test entity getters & setters.
     */
    public function testGettersSetters()
    {
        $entity = new User();

        $entity->setActivationKey('activation-key');
        $entity->setDisplayName('display name');
        $entity->setEmail('user@email.com');
        $entity->setLogin('login');

        $collection = new ArrayCollection();
        $meta1 = new UserMeta();
        $meta1->setKey('meta-key');
        $meta1->setValue('meta-value');
        $meta1->setUser($entity);
        $collection->add($entity);
        $entity->setMetas($collection);

        $entity->setNicename('nice name');
        $entity->setPass('pass');

        $date = new \DateTime();
        $entity->setRegistered($date);

        $entity->setStatus(2);
        $entity->setUrl('http://www.url.com');

        $this->assertEquals('activation-key', $entity->getActivationKey());
        $this->assertEquals('display name', $entity->getDisplayName());
        $this->assertEquals('user@email.com', $entity->getEmail());
        $this->assertEquals('login', $entity->getLogin());
        $this->assertEquals($collection, $entity->getMetas());
        $this->assertEquals('nice name', $entity->getNicename());
        $this->assertEquals('pass', $entity->getPass());
        $this->assertEquals($date, $entity->getRegistered());
        $this->assertEquals(2, $entity->getStatus());
        $this->assertEquals('http://www.url.com', $entity->getUrl());
    }

    /**
     * Test setting classic Symfony roles.
     */
    public function testSymfonyRoles()
    {
        $roles = ['ROLE_ADMINISTRATOR'];

        $user = new User();
        $user->setRoles($roles);

        $this->assertEquals(['ROLE_ADMINISTRATOR'], $user->getRoles());
    }

    /**
     * Test setting Wordpress roles and should return Symfony roles.
     */
    public function testWordpressRoles()
    {
        $wordpressRoles = ['author'];

        $user = new User();
        $user->setWordpressRoles($wordpressRoles);

        $this->assertEquals(['ROLE_WP_AUTHOR'], $user->getRoles());
    }
}
