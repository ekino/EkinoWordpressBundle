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
 * Class CommentMeta
 *
 * This is the CommentMeta entity
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
abstract class CommentMeta implements WordpressEntityInterface
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
     *
     * @return CommentMeta
     */
    public function setComment(Comment $comment)
    {
        $this->comment = $comment;

        return $this;
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
     *
     * @return CommentMeta
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
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
     *
     * @return CommentMeta
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
