<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle\Entity;

/**
 * Class TermRelationships
 *
 * This is the TermRelationships entity
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class TermRelationships implements WordpressEntityInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var TermTaxonomy
     */
    protected $taxonomy;

    /**
     * @var integer
     */
    protected $termOrder;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $termOrder
     */
    public function setTermOrder($termOrder)
    {
        $this->termOrder = $termOrder;
    }

    /**
     * @return int
     */
    public function getTermOrder()
    {
        return $this->termOrder;
    }

    /**
     * @param TermTaxonomy $taxonomy
     */
    public function setTaxonomy(TermTaxonomy $taxonomy)
    {
        $this->taxonomy = $taxonomy;
    }

    /**
     * @return TermTaxonomy
     */
    public function getTaxonomy()
    {
        return $this->taxonomy;
    }
}