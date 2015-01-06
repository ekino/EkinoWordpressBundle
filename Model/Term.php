<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Term
 *
 * This is the Term entity
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
abstract class Term implements WordpressEntityInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var integer
     */
    protected $group;

    /**
     * @var ArrayCollection
     */
    protected $taxonomies;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->taxonomies = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $group
     *
     * @return Term
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return int
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param string $slug
     *
     * @return Term
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $name
     *
     * @return Term
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param array $taxonomies
     *
     * @return Term
     */
    public function setTaxonomies($taxonomies)
    {
        $this->taxonomies = $taxonomies;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getTaxonomies()
    {
        return $this->taxonomies;
    }

    /**
     * @param TermTaxonomy $taxonomy
     *
     * @return Term
     */
    public function addTaxonomy(TermTaxonomy $taxonomy)
    {
        if (!$this->taxonomies->contains($taxonomy)) {
            $this->taxonomies[] = $taxonomy;
        }

        return $this;
    }

    /**
     * @param TermTaxonomy $taxonomy
     *
     * @return Term
     */
    public function removeTaxonomy(TermTaxonomy $taxonomy)
    {
        if ($this->taxonomies->contains($taxonomy)) {
            $this->taxonomies->remove($taxonomy);
        }

        return $this;
    }
}
