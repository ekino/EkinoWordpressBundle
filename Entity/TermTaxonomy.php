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
 * Class TermTaxonomy
 *
 * This is the TermTaxonomy entity
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class TermTaxonomy implements WordpressEntityInterface
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
     *
     * @return TermTaxonomy
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
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
     *
     * @return TermTaxonomy
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
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
     *
     * @return TermTaxonomy
     */
    public function setParent(TermTaxonomy $parent)
    {
        $this->parent = $parent;

        return $this;
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
     *
     * @return TermTaxonomy
     */
    public function setTaxonomy($taxonomy)
    {
        $this->taxonomy = $taxonomy;

        return $this;
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
     *
     * @return TermTaxonomy
     */
    public function setTerm(Term $term)
    {
        $this->term = $term;

        return $this;
    }

    /**
     * @return Term
     */
    public function getTerm()
    {
        return $this->term;
    }
}