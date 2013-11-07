<?php

namespace Ekino\WordpressBundle\Entity;

use Ekino\WordpressBundle\Entity\Post;

/**
 * Class PostMeta
 *
 * This is the PostMeta entity
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class PostMeta
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var Post
     */
    protected $post;

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
     * @param Post $post
     */
    public function setPost(Post $post)
    {
        $this->post = $post;
    }

    /**
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
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