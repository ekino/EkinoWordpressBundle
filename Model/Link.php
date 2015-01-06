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
 * Class Link
 *
 * This is the Link entity
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
abstract class Link implements WordpressEntityInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $image;

    /**
     * @var string
     */
    protected $target;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $visible;

    /**
     * @var integer
     */
    protected $owner;

    /**
     * @var integer
     */
    protected $rating;

    /**
     * @var \DateTime
     */
    protected $updated;

    /**
     * @var string
     */
    protected $rel;

    /**
     * @var string
     */
    protected $notes;

    /**
     * @var string
     */
    protected $rss;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $description
     *
     * @return Link
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
     * @param string $image
     *
     * @return Link
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $name
     *
     * @return Link
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
     * @param string $notes
     *
     * @return Link
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param int $owner
     *
     * @return Link
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return int
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param int $rating
     *
     * @return Link
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param string $rel
     *
     * @return Link
     */
    public function setRel($rel)
    {
        $this->rel = $rel;

        return $this;
    }

    /**
     * @return string
     */
    public function getRel()
    {
        return $this->rel;
    }

    /**
     * @param string $rss
     *
     * @return Link
     */
    public function setRss($rss)
    {
        $this->rss = $rss;

        return $this;
    }

    /**
     * @return string
     */
    public function getRss()
    {
        return $this->rss;
    }

    /**
     * @param string $target
     *
     * @return Link
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param \DateTime $updated
     *
     * @return Link
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param string $url
     *
     * @return Link
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $visible
     *
     * @return Link
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * @return string
     */
    public function getVisible()
    {
        return $this->visible;
    }
}
