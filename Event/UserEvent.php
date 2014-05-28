<?php

namespace DCS\OpauthFOSUBBundle\Event;

use FOS\UserBundle\Model\UserInterface;

class UserEvent extends AbstractResponseEvent
{
    /**
     * @var \FOS\UserBundle\Model\UserInterface
     */
    private $user;

    function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * @return \FOS\UserBundle\Model\UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }
}