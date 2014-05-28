<?php

namespace DCS\OpauthFOSUBBundle\Event;

use FOS\UserBundle\Model\UserInterface;

class SyncUserMismatchEvent extends AbstractResponseEvent
{
    /**
     * @var \FOS\UserBundle\Model\UserInterface
     */
    private $userSession;

    /**
     * @var \FOS\UserBundle\Model\UserInterface
     */
    private $userFound;

    function __construct(UserInterface $userSession, UserInterface $userFound)
    {
        $this->userSession = $userSession;
        $this->userFound = $userFound;
    }

    /**
     * @return \FOS\UserBundle\Model\UserInterface
     */
    public function getUserSession()
    {
        return $this->userSession;
    }

    /**
     * @return \FOS\UserBundle\Model\UserInterface
     */
    public function getUserFound()
    {
        return $this->userFound;
    }
}