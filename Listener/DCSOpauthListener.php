<?php

namespace DCS\OpauthFOSUBBundle\Listener;

use DCS\OpauthBundle\DCSOpauthEvents;
use DCS\OpauthBundle\Event\OpauthResponseEvent;
use DCS\OpauthFOSUBBundle\DCSOpauthFOSUBEvents;
use DCS\OpauthFOSUBBundle\Event\OpauthResponseDataEvent;
use DCS\OpauthFOSUBBundle\Event\SyncUserMismatchEvent;
use DCS\OpauthFOSUBBundle\Event\UserEvent;
use DCS\OpauthFOSUBBundle\Model\OauthManagerInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Security\LoginManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;

class DCSOpauthListener implements EventSubscriberInterface
{
    /**
     * @var \Symfony\Component\Security\Core\SecurityContextInterface
     */
    protected $security;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var \DCS\OpauthFOSUBBundle\Model\OauthManagerInterface
     */
    protected $oauthManager;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    protected $router;

    /**
     * @var \FOS\UserBundle\Security\LoginManagerInterface
     */
    protected $loginManager;

    /**
     * @var UserCheckerInterface
     */
    protected $userChecker;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var string
     */
    protected $firewallName;

    function __construct(
        SecurityContextInterface $security,
        EventDispatcherInterface $eventDispatcher,
        OauthManagerInterface $oauthManager,
        RouterInterface $router,
        LoginManagerInterface $loginManager,
        UserCheckerInterface $userChecker,
        SessionInterface $session,
        $firewallName
    ) {
        $this->security = $security;
        $this->eventDispatcher = $eventDispatcher;
        $this->oauthManager = $oauthManager;
        $this->router = $router;
        $this->loginManager = $loginManager;
        $this->userChecker = $userChecker;
        $this->session = $session;
        $this->firewallName = $firewallName;
    }

    public static function getSubscribedEvents()
    {
        return array(
            DCSOpauthEvents::AFTER_PARSE_RESPONSE => array('onAfterParseResponse'),
        );
    }

    public function onAfterParseResponse(OpauthResponseEvent $event)
    {
        if (!$event->isValid()) {
            return;
        }

        $responseData = $event->getResponseData();

        $provider = $responseData['auth']['provider'];
        $uid = $responseData['auth']['uid'];

        $token = $this->security->getToken();

        // Check if user is logged
        if ($this->checkToken($token)) {
            // Disable authentication executed by DCSOpauthBundle:Response controller
            $event->setAuthenticate(false);

            // If there is no user with the UID and the provider run the synchronization
            if (null === $user = $this->oauthManager->findUserByUidProvider($uid, $provider)) {
                $user = $this->security->getToken()->getUser();
                $this->oauthManager->updateUser($user, $uid, $provider, $responseData);

                $syncUserSuccessful = new UserEvent($user);
                $this->eventDispatcher->dispatch(DCSOpauthFOSUBEvents::SYNC_USER_SUCCESSFUL, $syncUserSuccessful);

                $event->setResponse($syncUserSuccessful->getResponse());
            } else {
                // if the current user is a different user found by the combination of UID and provider launch the SYNC_USER_MISMATCH event
                if ($user != $currentUser = $this->security->getToken()->getUser()) {
                    $syncUserMismatch = new SyncUserMismatchEvent($currentUser, $user);
                    $this->eventDispatcher->dispatch(DCSOpauthFOSUBEvents::SYNC_USER_MISMATCH, $syncUserMismatch);

                    $event->setResponse($syncUserMismatch->getResponse());
                }
            }
        } else {
            // If the User was not found by uid and provider, redirect the user on fos registration
            if (null === $user = $this->oauthManager->findUserByUidProvider($uid, $provider)) {
                $opauthResponseDataEvent = new OpauthResponseDataEvent($responseData);
                $this->eventDispatcher->dispatch(DCSOpauthFOSUBEvents::NOT_FOUND_USER_BY_PROVIDER, $opauthResponseDataEvent);
                // if not set a new response, set the default response
                if (null === $response = $opauthResponseDataEvent->getResponse()) {
                    $response = new RedirectResponse($this->router->generate('fos_user_registration_register'));
                }
                $event->setResponse($response);
            } else {
                // Execute the login and disable Opauth authentication
                $this->doLogin($user, $event);
                $event->setAuthenticate(false);

                $userEvent = new UserEvent($user);
                $this->eventDispatcher->dispatch(DCSOpauthFOSUBEvents::AFTER_LOGIN, $userEvent);

                $event->setResponse($userEvent->getResponse());
            }
        }
    }

    /**
     * Check if current token is instance of UsernamePasswordToken
     *
     * @param AbstractToken $token
     * @return bool
     */
    protected function checkToken(AbstractToken $token)
    {
        return $token instanceof UsernamePasswordToken;
    }

    /**
     * Execute the login with loginManager of FOSUserBundle
     *
     * @param UserInterface $user
     * @param OpauthResponseEvent $event
     */
    protected function doLogin(UserInterface $user, OpauthResponseEvent $event)
    {
        try {
            $this->userChecker->checkPreAuth($user);
            $this->loginManager->loginUser($this->firewallName, $user);
        } catch (AccountStatusException $e) {
            if (null !== $this->session && !$this->session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
                $this->session->set(SecurityContextInterface::AUTHENTICATION_ERROR, $e);
            }
            throw $e;
        }
    }
}
