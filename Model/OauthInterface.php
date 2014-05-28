<?php

namespace DCS\OpauthFOSUBBundle\Model;

use FOS\UserBundle\Model\UserInterface;

interface OauthInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * Set provider
     *
     * @param string $provider
     */
    public function setProvider($provider);

    /**
     * Get provider
     *
     * @return string
     */
    public function getProvider();

    /**
     * Set UID
     *
     * @param string $uid
     */
    public function setUid($uid);

    /**
     * Get UID
     *
     * @return string
     */
    public function getUid();

    /**
     * Set User
     *
     * @param \FOS\UserBundle\Model\UserInterface $user
     */
    public function setUser(UserInterface $user);

    /**
     * Get User
     *
     * @return \FOS\UserBundle\Model\UserInterface
     */
    public function getUser();

    /**
     * Set raw
     *
     * @param array|string $raw
     */
    public function setRaw($raw = null);

    /**
     * Get raw
     *
     * @return array|string
     */
    public function getRaw();
} 