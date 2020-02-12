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

use Ekino\WordpressBundle\Entity\Link;

/**
 * Class LinkTest.
 *
 * This is the Wordpress link entity test
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class LinkTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test entity getters & setters.
     */
    public function testGettersSetters()
    {
        $entity = new Link();

        $entity->setDescription('link description');
        $entity->setImage('link image');
        $entity->setName('link name');
        $entity->setNotes('link notes');
        $entity->setOwner('link owner');
        $entity->setRating('link rating');
        $entity->setRel('link rel');
        $entity->setRss('link rss');
        $entity->setTarget('link target');

        $date = new \DateTime();
        $entity->setUpdated($date);

        $entity->setUrl('link url');
        $entity->setVisible('link visible');

        $this->assertEquals('link description', $entity->getDescription());
        $this->assertEquals('link image', $entity->getImage());
        $this->assertEquals('link name', $entity->getName());
        $this->assertEquals('link notes', $entity->getNotes());
        $this->assertEquals('link owner', $entity->getOwner());
        $this->assertEquals('link rating', $entity->getRating());
        $this->assertEquals('link rel', $entity->getRel());
        $this->assertEquals('link rss', $entity->getRss());
        $this->assertEquals('link target', $entity->getTarget());
        $this->assertEquals($date, $entity->getUpdated());
        $this->assertEquals('link url', $entity->getUrl());
        $this->assertEquals('link visible', $entity->getVisible());
    }
}
