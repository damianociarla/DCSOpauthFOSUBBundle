<?php

namespace DCS\OpauthFOSUBBundle\Model;

use FOS\UserBundle\Model\UserInterface;

interface OauthManagerInterface
{
    /**
     * Find User by UID and Provider
     *
     * @param $uid
     * @param $provider
     * @return UserInterface|null
     */
    public function findUserByUidProvider($uid, $provider);

    /**
     * Create or update User with the Opauth informations
     *
     * @param UserInterface $user
     * @param $uid
     * @param $provider
     * @param array $raw
     */
    public function updateUser(UserInterface $user, $uid, $provider, array $raw = null);

    /**
     * Find Oauth config by User
     *
     * @param UserInterface $user
     * @return array
     */
    public function findOauthConfigurationsByUser(UserInterface $user);

    /**
     * Find Oauth by uid and provider
     *
     * @param $uid
     * @param $provider
     * @return null|OauthInterface
     */
    public function findOauth($uid, $provider);

    /**
     * Find Oauth by User and provider
     *
     * @param UserInterface $user
     * @param string $provider
     * @return null|OauthInterface
     */
    public function findOauthByUserAndProvider(UserInterface $user, $provider);

    /**
     * Remove Oauth instance
     *
     * @param OauthInterface $oauth
     */
    public function removeOauth(OauthInterface $oauth);
} 