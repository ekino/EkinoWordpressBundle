<?php

namespace Ekino\WordpressBundle\Tests\Entity;

use Ekino\WordpressBundle\Entity\TermRelationships;
use Ekino\WordpressBundle\Entity\TermTaxonomy;

/**
 * Class TermRelationshipsTest
 *
 * This is the Wordpress term relationships entity test
 */
class TermRelationshipsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test entity getters & setters
     */
    public function testGettersSetters()
    {
        $entity = new TermRelationships();

        $taxonomy = new TermTaxonomy();
        $taxonomy->setDescription('taxonomy description');
        $entity->setTaxonomy($taxonomy);

        $entity->setTermOrder(4);

        $this->assertEquals('taxonomy description', $entity->getTaxonomy()->getDescription());
        $this->assertEquals(4, $entity->getTermOrder());
    }
}