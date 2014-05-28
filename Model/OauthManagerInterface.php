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
} 