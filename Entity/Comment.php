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
 * Class Comment
 *
 * This is the Comment entity
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class Comment implements WordpressEntityInterface
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
    protected $author;

    /**
     * @var string
     */
    protected $authorEmail;

    /**
     * @var string
     */
    protected $authorUrl;

    /**
     * @var string
     */
    protected $authorIp;

    /**
     * @var \DateTime
     */
    protected $date;

    /**
     * @var \DateTime
     */
    protected $dateGmt;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var integer
     */
    protected $karma;

    /**
     * @var string
     */
    protected $approved;

    /**
     * @var string
     */
    protected $agent;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var Comment
     */
    protected $parent;

    /**
     * @var User
     */
    protected $user;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $agent
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
    }

    /**
     * @return string
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * @param string $approved
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;
    }

    /**
     * @return string
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $authorEmail
     */
    public function setAuthorEmail($authorEmail)
    {
        $this->authorEmail = $authorEmail;
    }

    /**
     * @return string
     */
    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }

    /**
     * @param string $authorIp
     */
    public function setAuthorIp($authorIp)
    {
        $this->authorIp = $authorIp;
    }

    /**
     * @return string
     */
    public function getAuthorIp()
    {
        return $this->authorIp;
    }

    /**
     * @param string $authorUrl
     */
    public function setAuthorUrl($authorUrl)
    {
        $this->authorUrl = $authorUrl;
    }

    /**
     * @return string
     */
    public function getAuthorUrl()
    {
        return $this->authorUrl;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $dateGmt
     */
    public function setDateGmt($dateGmt)
    {
        $this->dateGmt = $dateGmt;
    }

    /**
     * @return \DateTime
     */
    public function getDateGmt()
    {
        return $this->dateGmt;
    }

    /**
     * @param int $karma
     */
    public function setKarma($karma)
    {
        $this->karma = $karma;
    }

    /**
     * @return int
     */
    public function getKarma()
    {
        return $this->karma;
    }

    /**
     * @param Comment $parent
     */
    public function setParent(Comment $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return Comment
     */
    public function getParent()
    {
        return $this->parent;
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
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}