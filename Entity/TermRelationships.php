<?php

namespace Ekino\WordpressBundle\Entity;

use Ekino\WordpressBundle\Entity\TermTaxonomy;

/**
 * Class TermRelationships
 *
 * This is the TermRelationships entity
 */
class TermRelationships
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