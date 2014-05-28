<?php

namespace DCS\OpauthFOSUBBundle\Listener;

use DCS\OpauthBundle\Security\OpauthToken;
use DCS\OpauthFOSUBBundle\Model\OauthManagerInterface;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

class FOSUserListener implements EventSubscriberInterface
{
    protected $security;

    protected $oauthManager;

    public function __construct(SecurityContextInterface $security, OauthManagerInterface $oauthManager)
    {
        $this->security = $security;
        $this->oauthManager = $oauthManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_INITIALIZE => array('onRegistrationInitialize'),
            FOSUserEvents::REGISTRATION_COMPLETED => array('onRegistrationCompleted'),
        );
    }

    public function onRegistrationInitialize(GetResponseUserEvent $event)
    {
        $token = $this->security->getToken();

        if ($token instanceof OpauthToken) {
            $oauthInfo = $token->getInfo();

            $user = $event->getUser();
            $user->setEmail(isset($oauthInfo['email']) ? $oauthInfo['email'] : null);
            $user->setUsername(isset($oauthInfo['nickname']) ? $oauthInfo['nickname'] : null);
        }
    }

    public function onRegistrationCompleted(FilterUserResponseEvent $event)
    {
        $token = $this->security->getToken();

        if ($token instanceof OpauthToken) {
            $this->oauthManager->updateUser($event->getUser(), $token->getUid(), $token->getProvider(), $token->getRaw());
        }
    }
} 