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

use Ekino\WordpressBundle\Entity\Post;
use Ekino\WordpressBundle\Entity\PostMeta;

/**
 * Class PostMetaTest.
 *
 * This is the Wordpress post meta entity test
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class PostMetaTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test entity getters & setters.
     */
    public function testGettersSetters()
    {
        $entity = new PostMeta();

        $post = new Post();
        $post->setTitle('post title');
        $entity->setPost($post);

        $entity->setKey('fake key');
        $entity->setValue('fake value');

        $this->assertEquals('post title', $entity->getPost()->getTitle());
        $this->assertEquals('fake key', $entity->getKey());
        $this->assertEquals('fake value', $entity->getValue());
    }
}
