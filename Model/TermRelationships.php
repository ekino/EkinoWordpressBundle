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

/**
 * Class TermRelationships
 *
 * This is the TermRelationships entity
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
abstract class TermRelationships implements WordpressEntityInterface
{
    /**
     * @var TermTaxonomy
     */
    protected $taxonomy;

    /**
     * @var integer
     */
    protected $termOrder;

    /**
     * @var Post
     */
    protected $post;

    /**
     * @param Post $post
     *
     * @return TermRelationships
     */
    public function setPost(Post $post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param int $termOrder
     *
     * @return TermRelationships
     */
    public function setTermOrder($termOrder)
    {
        $this->termOrder = $termOrder;

        return $this;
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
     *
     * @return TermRelationships
     */
    public function setTaxonomy(TermTaxonomy $taxonomy)
    {
        $this->taxonomy = $taxonomy;

        return $this;
    }

    /**
     * @return TermTaxonomy
     */
    public function getTaxonomy()
    {
        return $this->taxonomy;
    }
}
