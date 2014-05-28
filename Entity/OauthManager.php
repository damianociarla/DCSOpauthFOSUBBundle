<?php

namespace DCS\OpauthFOSUBBundle\Entity;

use DCS\OpauthFOSUBBundle\Model\OauthInterface;
use Doctrine\ORM\EntityManager;
use DCS\OpauthFOSUBBundle\Model\OauthManager as BaseManager;

class OauthManager extends BaseManager
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $repository;

    public function __construct(EntityManager $em, $class)
    {
        $this->em = $em;
        $this->repository = $em->getRepository($class);

        $metadata = $em->getClassMetadata($class);
        $this->class = $metadata->name;
    }

    /**
     * Find Oauth by uid and provider
     *
     * @param $uid
     * @param $provider
     * @return null|OauthInterface
     */
    protected function findOauth($uid, $provider)
    {
        return $this->repository->findOneBy(array(
            'provider' => $provider,
            'uid' => $uid,
        ));
    }

    /**
     * Create or update Oauth
     *
     * @param OauthInterface $oauth
     */
    public function doPersist(OauthInterface $oauth)
    {
        $this->em->persist($oauth);
        $this->em->flush();
    }
} 