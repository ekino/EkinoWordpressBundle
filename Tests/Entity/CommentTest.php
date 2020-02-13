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

use Ekino\WordpressBundle\Entity\Comment;
use Ekino\WordpressBundle\Entity\Post;
use Ekino\WordpressBundle\Entity\User;

/**
 * Class CommentTest.
 *
 * This is the Wordpress comment entity test
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class CommentTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test entity getters & setters.
     */
    public function testGettersSetters()
    {
        $entity = new Comment();

        $entity->setAgent('agent');
        $entity->setApproved('approved');
        $entity->setAuthor('author');
        $entity->setAuthorEmail('author@email.com');
        $entity->setAuthorIp('1.2.3.4');
        $entity->setAuthorUrl('http://author.com');
        $entity->setContent('content');

        $date = new \DateTime();
        $entity->setDate($date);
        $entity->setDateGmt($date);

        $entity->setKarma(2);

        $parent = new Comment();
        $parent->setContent('parent content');
        $entity->setParent($parent);

        $post = new Post();
        $post->setTitle('post title');
        $entity->setPost($post);

        $entity->setType('type');

        $user = new User();
        $user->setDisplayName('author name');
        $entity->setUser($user);

        $this->assertEquals('agent', $entity->getAgent());
        $this->assertEquals('approved', $entity->getApproved());
        $this->assertEquals('author', $entity->getAuthor());
        $this->assertEquals('author@email.com', $entity->getAuthorEmail());
        $this->assertEquals('1.2.3.4', $entity->getAuthorIp());
        $this->assertEquals('http://author.com', $entity->getAuthorUrl());
        $this->assertEquals('content', $entity->getContent());
        $this->assertEquals($date, $entity->getDate());
        $this->assertEquals($date, $entity->getDateGmt());
        $this->assertEquals(2, $entity->getKarma());
        $this->assertEquals('parent content', $entity->getParent()->getContent());
        $this->assertEquals('post title', $entity->getPost()->getTitle());
        $this->assertEquals('type', $entity->getType());
        $this->assertEquals('author name', $entity->getUser()->getDisplayName());
    }
}
