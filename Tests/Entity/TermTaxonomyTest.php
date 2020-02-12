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

use Ekino\WordpressBundle\Entity\Term;
use Ekino\WordpressBundle\Entity\TermTaxonomy;

/**
 * Class TermTaxonomyTest.
 *
 * This is the Wordpress term taxonomy entity test
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class TermTaxonomyTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test entity getters & setters.
     */
    public function testGettersSetters()
    {
        $entity = new TermTaxonomy();

        $entity->setCount(5);
        $entity->setDescription('term description');

        $parent = new TermTaxonomy();
        $parent->setDescription('parent term description');
        $entity->setParent($parent);

        $entity->setTaxonomy('term taxonomy');

        $term = new Term();
        $term->setName('term name');
        $entity->setTerm($term);

        $this->assertEquals(5, $entity->getCount());
        $this->assertEquals('term description', $entity->getDescription());
        $this->assertEquals('parent term description', $entity->getParent()->getDescription());
        $this->assertEquals('term taxonomy', $entity->getTaxonomy());
        $this->assertEquals('term name', $entity->getTerm()->getName());
    }
}
