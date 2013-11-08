<?php

namespace Ekino\WordpressBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 *
 * This is the User entity
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class User implements UserInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $login;

    /**
     * @var string
     */
    protected $pass;

    /**
     * @var string
     */
    protected $nicename;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var \DateTime
     */
    protected $registered;

    /**
     * @var string
     */
    protected $activationKey;

    /**
     * @var integer
     */
    protected $status;

    /**
     * @var string
     */
    protected $displayName;

    /**
     * @var ArrayCollection
     */
    protected $metas;

    /**
     * @var array
     */
    protected $roles;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->metas = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $activationKey
     */
    public function setActivationKey($activationKey)
    {
        $this->activationKey = $activationKey;
    }

    /**
     * @return string
     */
    public function getActivationKey()
    {
        return $this->activationKey;
    }

    /**
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $nicename
     */
    public function setNicename($nicename)
    {
        $this->nicename = $nicename;
    }

    /**
     * @return string
     */
    public function getNicename()
    {
        return $this->nicename;
    }

    /**
     * @param string $pass
     */
    public function setPass($pass)
    {
        $this->pass = $pass;
    }

    /**
     * @return string
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * @param \DateTime $registered
     */
    public function setRegistered($registered)
    {
        $this->registered = $registered;
    }

    /**
     * @return \DateTime
     */
    public function getRegistered()
    {
        return $this->registered;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param ArrayCollection $metas
     */
    public function setMetas(ArrayCollection $metas)
    {
        $this->metas = $metas;
    }

    /**
     * Returns user meta value from a meta key name
     *
     * @param string $name
     *
     * @return string|null
     */
    public function getMetaValue($name)
    {
        foreach ($this->getMetas() as $meta) {
            if ($name == $meta->getKey()) {
                return $meta->getValue();
            }
        }

        return null;
    }

    /**
     * @return ArrayCollection
     */
    public function getMetas()
    {
        return $this->metas;
    }

    /**
     * Sets user roles
     *
     * @param array|string $roles
     */
    public function setRoles($roles)
    {
        if (!is_array($roles)) {
            $roles = array($roles);
        }

        $this->roles = $roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->roles ? $this->roles : array();
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->getPass();
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * {@inheritdoc}e
     */
    public function getUsername()
    {
        return $this->getLogin();
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {

    }
}