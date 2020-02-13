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

use Ekino\WordpressBundle\Entity\TermRelationships;
use Ekino\WordpressBundle\Entity\TermTaxonomy;

/**
 * Class TermRelationshipsTest.
 *
 * This is the Wordpress term relationships entity test
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class TermRelationshipsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test entity getters & setters.
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
