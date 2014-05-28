<?php

namespace DCS\OpauthFOSUBBundle\Model;

use FOS\UserBundle\Model\UserInterface;

class Oauth implements OauthInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var UserInterface
     */
    protected $user;

    /**
     * @var string
     */
    protected $provider;

    /**
     * @var string
     */
    protected $uid;

    /**
     * @var string|array
     */
    protected $raw;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set provider
     *
     * @param string $provider
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
    }

    /**
     * Get provider
     *
     * @return string
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Set UID
     *
     * @param string $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * Get UID
     *
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set User
     *
     * @param \FOS\UserBundle\Model\UserInterface $user
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * Get User
     *
     * @return \FOS\UserBundle\Model\UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set raw
     *
     * @param array|string $raw
     */
    public function setRaw($raw = null)
    {
        $this->raw = $raw;
    }

    /**
     * Get raw
     *
     * @return array|string
     */
    public function getRaw()
    {
        return $this->raw;
    }
}