<?php

namespace Ekino\WordpressBundle\Entity;

use Ekino\WordpressBundle\Entity\Term;

/**
 * Class TermTaxonomy
 *
 * This is the TermTaxonomy entity
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class TermTaxonomy
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var integer
     */
    protected $term;

    /**
     * @var string
     */
    protected $taxonomy;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var TermTaxonomy
     */
    protected $parent;

    /**
     * @var integer
     */
    protected $count;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param TermTaxonomy $parent
     */
    public function setParent(TermTaxonomy $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return TermTaxonomy
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param string $taxonomy
     */
    public function setTaxonomy($taxonomy)
    {
        $this->taxonomy = $taxonomy;
    }

    /**
     * @return string
     */
    public function getTaxonomy()
    {
        return $this->taxonomy;
    }

    /**
     * @param Term $term
     */
    public function setTerm(Term $term)
    {
        $this->term = $term;
    }

    /**
     * @return Term
     */
    public function getTerm()
    {
        return $this->term;
    }
}