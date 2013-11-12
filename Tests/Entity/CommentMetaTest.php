<?php

namespace Ekino\WordpressBundle\Tests\Entity;

use Ekino\WordpressBundle\Entity\Comment;
use Ekino\WordpressBundle\Entity\CommentMeta;

/**
 * Class CommentMetaTest
 *
 * This is the Wordpress comment meta entity test
 */
class CommentMetaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test entity getters & setters
     */
    public function testGettersSetters()
    {
        $entity = new CommentMeta();

        $comment = new Comment();
        $comment->setContent('comment message');
        $entity->setComment($comment);

        $entity->setKey('fake key');
        $entity->setValue('fake value');

        $this->assertEquals('comment message', $entity->getComment()->getContent());
        $this->assertEquals('fake key', $entity->getKey());
        $this->assertEquals('fake value', $entity->getValue());
    }
}