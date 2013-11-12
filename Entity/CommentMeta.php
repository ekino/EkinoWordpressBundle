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

use Ekino\WordpressBundle\Entity\Comment;

/**
 * Class CommentMeta
 *
 * This is the CommentMeta entity
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class CommentMeta
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var Comment
     */
    protected $comment;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $value;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Comment $comment
     */
    public function setComment(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return Comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}