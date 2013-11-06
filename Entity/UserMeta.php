<?php

namespace Ekino\WordpressBundle\Entity;

use Ekino\WordpressBundle\Entity\User;

/**
 * Class UserMeta
 *
 * This is the UserMeta entity
 */
class UserMeta
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var User
     */
    protected $user;

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