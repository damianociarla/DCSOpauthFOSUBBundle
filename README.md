DCSOpauthFOSUBBundle
====================

**DCSOpauthFOSUBBundle** is a Symfony2 bundle that integrates [DCSOpauthBundle](https://github.com/damianociarla/DCSOpauthBundle) with [FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle/) and allows users to login/register to your site using third party authentication.

This bundle uses an Entity, related to the FOS user table, to store the user's data retrieved by the authentication service.

This bundle does not modify the FOSUserBundle login/registration but allows the user to synchronize with providers enabled by the application.

## Prerequisites

To use DCSOpauthFOSUBBundle you need to download, install and configure DCSOpauthBundle and FOSUserBundle.

For specific documentation refer to the documentation of the individual bundles

## Installation

### a) Download and install DCSOpauthFOSUBBundle

To install DCSOpauthBundle run the following command

	bash $ php composer.phar require damianociarla/opauth-fosub-bundle

### b) Enable the bundle

To enable it add the bundle instance in the kernel:

	<?php
	// app/AppKernel.php

	public function registerBundles()
	{
	    $bundles = array(
        	// ...
        	new DCS\OpauthFOSUBBundle\DCSOpauthFOSUBBundle(),
    	);
	}

### c) Create your Oauth classes

In this first release DCSOpauthFOSUBBundle supports only Doctrine ORM. You must provide a concrete Oauth class. You must extend the abstract entity provided by the bundle and create the appropriate mapping.

#### Annotations

`src/Acme/OpauthFOSUBBundle/Entity/Oauth.php`

    <?php

    namespace Acme\OpauthFOSUBBundle\Entity;

    use DCS\OpauthFOSUBBundle\Entity\Oauth as BaseOauth;
    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Entity
     * @ORM\Table(name="fos_user_oauth")
     */
    class Oauth extends BaseOauth
    {
        /**
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        protected $id;

        /**
         * @ORM\ManyToOne(targetEntity="Acme\UserBundle\Entity\User")
         * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable="false")
         */
        protected $user;

        public function __construct()
        {
            parent::__construct();
            // your own logic
        }
    }

#### xml

If you use xml to configure Doctrine you must add two files. The Entity and the orm.xml:

`src/Acme/OpauthFOSUBBundle/Entity/Oauth.php`

    <?php

    namespace Acme\OpauthFOSUBBundle\Entity;

    use DCS\OpauthFOSUBBundle\Entity\Oauth as BaseOauth;

    class Oauth extends BaseOauth
    {
    }

`src/Acme/OpauthFOSUBBundle/Resources/config/doctrine/Oauth.orm.yml`

    <?xml version="1.0" encoding="UTF-8"?>

    <doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                      xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping http://doctrine-project.org/schemas/orm/doctrine-mapping.xsd">

        <entity name="Acme\OpauthFOSUBBundle\Entity\Oauth" table="fos_user_oauth">

            <id name="id" type="integer" column="id">
                <generator strategy="AUTO" />
            </id>

            <many-to-one field="user" target-entity="Acme\UserBundle\Entity\User">
                <join-column name="user_id" referenced-column-name="id" nullable="false" />
            </many-to-one>

        </entity>

    </doctrine-mapping>

### d) Configure the bundle

Now that you have properly enabled and configured the DCSOpauthFOSUBBundle, the next step is to configure the bundle to work with the specific needs of your application.

Add the following configuration to your config.yml file.

    # app/config/config.yml

    dcs_opauth_fosub:
        oauth_model: Acme\OpauthFOSUBBundle\Entity\Oauth

## How does it work?

DCSOpauthFOSUBBundle doesn't change the FOSUserBundle registration flow but adds the possibility to synchronize and register using external authentication providers (Facebook, Google, LinkedIn) using the `connect` route provided by DCSOpauth.

If the user is not authenticated, after authenticating with DCSOpauthBundle, DCSOpauthFOSUBBundle checks if a user with that UID exists, if it does exist the user gets logged-in, if it does not exist, the FOSUserBundle registration form is opened. At the end of the FOSUserBundle registration a record with the provider name and UID is created and associated with the new user.

If the user is already authenticated, after authenticating with DCSOpauthBundle, DCSOpauthFOSUBBundle associates the logged-in user with the UID and provider name.