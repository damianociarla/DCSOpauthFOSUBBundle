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
     * Find Oauth by criteria
     *
     * @param array $criteria
     * @return null|OauthInterface
     */
    protected function findOneBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Find Oauth configurations by criteria
     *
     * @param array $criteria
     * @return array
     */
    protected function findBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * Remove Oauth instance
     *
     * @param OauthInterface $oauth
     */
    public function removeOauth(OauthInterface $oauth)
    {
        $this->em->remove($oauth);
        $this->em->flush();
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