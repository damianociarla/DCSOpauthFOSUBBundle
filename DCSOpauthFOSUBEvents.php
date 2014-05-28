<?php

namespace DCS\OpauthFOSUBBundle;

class DCSOpauthFOSUBEvents
{
    /**
     * The AFTER_LOGIN event occurs after the login by UID and PROVIDER
     *
     * The event listener method receives a DCS\OpauthFOSUBBundle\Event\UserEvent instance.
     */
    const AFTER_LOGIN = 'dcs_opauth_fosub.event.after_login';

    /**
     * The SYNC_USER_MISMATCH event occurs when the user is logged but the UID and PROVIDER are used by another user
     *
     * The event listener method receives a DCS\OpauthFOSUBBundle\Event\SyncUserMismatchEvent instance.
     */
    const SYNC_USER_MISMATCH = 'dcs_opauth_fosub.event.sync_user_mismatch';

    /**
     * The SYNC_USER_MISMATCH event occurs when the user is logged and the UID and PROVIDER are synced successfully with her account
     *
     * The event listener method receives a DCS\OpauthFOSUBBundle\Event\UserEvent instance.
     */
    const SYNC_USER_SUCCESSFUL = 'dcs_opauth_fosub.event.sync_user_successful';
}