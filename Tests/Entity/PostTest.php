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
use Ekino\WordpressBundle\Entity\Post;
use Ekino\WordpressBundle\Entity\PostMeta;
use Ekino\WordpressBundle\Entity\User;

/**
 * Class PostTest.
 *
 * This is the Wordpress post entity test
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class PostTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test entity getters & setters.
     */
    public function testGettersSetters()
    {
        $entity = new Post();

        $user = new User();
        $user->setDisplayName('author name');
        $entity->setAuthor($user);

        $entity->setCommentCount(5);
        $entity->setCommentStatus('approved');
        $entity->setContent('post content');
        $entity->setContentFiltered('post content filtered');

        $date = new \DateTime();
        $entity->setDate($date);
        $entity->setDateGmt($date);

        $entity->setExcerpt('excerpt');
        $entity->setGuid('guid');
        $entity->setMenuOrder(2);

        $meta1 = new PostMeta();
        $meta1->setKey('meta key');
        $meta1->setValue('meta value');
        $meta1->setPost($entity);

        $collection = new ArrayCollection();
        $collection->add($meta1);

        $entity->setMetas($collection);
        $entity->setMimeType('text/html');
        $entity->setModified($date);
        $entity->setModifiedGmt($date);
        $entity->setName('post name');

        $parent = new Post();
        $parent->setTitle('parent post title');
        $entity->setParent($parent);

        $entity->setPassword('password');
        $entity->setPinged('pinged');
        $entity->setPingStatus('done');
        $entity->setStatus('published');
        $entity->setTitle('post title');
        $entity->setToPing('to ping');
        $entity->setType('post type');

        $this->assertEquals('author name', $entity->getAuthor()->getDisplayName());
        $this->assertEquals(5, $entity->getCommentCount());
        $this->assertEquals('approved', $entity->getCommentStatus());
        $this->assertEquals('post content', $entity->getContent());
        $this->assertEquals('post content filtered', $entity->getContentFiltered());
        $this->assertEquals($date, $entity->getDate());
        $this->assertEquals($date, $entity->getDateGmt());
        $this->assertEquals('excerpt', $entity->getExcerpt());
        $this->assertEquals('guid', $entity->getGuid());
        $this->assertEquals(2, $entity->getMenuOrder());
        $this->assertEquals($collection, $entity->getMetas());
        $this->assertEquals('text/html', $entity->getMimeType());
        $this->assertEquals($date, $entity->getModified());
        $this->assertEquals($date, $entity->getModifiedGmt());
        $this->assertEquals('post name', $entity->getName());
        $this->assertEquals('parent post title', $entity->getParent()->getTitle());
        $this->assertEquals('password', $entity->getPassword());
        $this->assertEquals('pinged', $entity->getPinged());
        $this->assertEquals('done', $entity->getPingStatus());
        $this->assertEquals('published', $entity->getStatus());
        $this->assertEquals('post title', $entity->getTitle());
        $this->assertEquals('to ping', $entity->getToPing());
        $this->assertEquals('post type', $entity->getType());
    }
}
