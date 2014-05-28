<?php

namespace DCS\OpauthFOSUBBundle\Model;

use FOS\UserBundle\Model\UserInterface;

abstract class OauthManager implements OauthManagerInterface
{
    /**
     * @var string
     */
    protected $class;

    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * Find User by UID and Provider
     *
     * @param $uid
     * @param $provider
     * @return UserInterface|null
     */
    public function findUserByUidProvider($uid, $provider)
    {
        if (null !== $oauth = $this->findOauth($uid, $provider)) {
            return $oauth->getUser();
        }

        return null;
    }

    /**
     * Create or update User with the Opauth informations
     *
     * @param UserInterface $user
     * @param $uid
     * @param $provider
     * @param array $raw
     */
    public function updateUser(UserInterface $user, $uid, $provider, array $raw = null)
    {
        if (null === $oauth = $this->findOauth($uid, $provider)) {
            $oauth = new $this->class;
            $oauth->setUser($user);
            $oauth->setUid($uid);
            $oauth->setProvider($provider);
        }

        $oauth->setRaw($raw);

        $this->doPersist($oauth);
    }

    /**
     * Find Oauth by uid and provider
     *
     * @param $uid
     * @param $provider
     * @return null|OauthInterface
     */
    abstract protected function findOauth($uid, $provider);

    /**
     * Create or update Oauth
     *
     * @param OauthInterface $oauth
     */
    abstract protected function doPersist(OauthInterface $oauth);
} 