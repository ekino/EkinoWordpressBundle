<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle\Manager;

use Doctrine\ORM\EntityManager;

/**
 * Class BaseManager
 *
 * This is the base manager
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class BaseManager
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var string Class name
     */
    protected $class;

    /**
     * Constructor
     *
     * @param EntityManager $em    Entity manager
     * @param string        $class Class name
     */
    public function __construct(EntityManager $em, $class)
    {
        $this->class      = $class;
        $this->em         = $em;
        $this->repository = $em->getRepository($this->class);
    }

    /**
     * Returns a new non-managed entity
     *
     * @return mixed
     */
    public function create()
    {
        return new $this->class();
    }

    /**
     * Returns a "fresh" entity by identifier
     *
     * @param integer $id Entity identifier
     *
     * @return object
     */
    public function find($id)
    {
        return $this->repository->findOneById($id);
    }

    /**
     * Returns an entity by id
     *
     * @param integer $id Entity id
     *
     * @return object
     */
    public function findOneById($id)
    {
        return $this->repository->findOneById($id);
    }

    /**
     * Returns all entities of the child class
     *
     * @return array
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    /**
     * Returns entities found for given criteria
     *
     * @param array $criteria
     *
     * @return object
     */
    public function findBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * Returns entity found for given criteria
     *
     * @param array $criteria
     *
     * @return object
     */
    public function findOneBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Flush persisted entities
     */
    public function flush()
    {
        $this->em->flush();
    }

    /**
     * Refresh persisted entities
     */
    public function refresh($entity)
    {
        $this->em->refresh($entity);
    }

    /**
     * Clears the repository, causing all managed entities to become detached.
     */
    public function clear()
    {
        $this->em->clear();
    }

    /**
     * Persist the given entity
     *
     * @param mixed $entity  An entity instance
     * @param bool  $doFlush Also flush  entity manager?
     */
    public function save($entity, $doFlush = true)
    {
        $this->em->persist($entity);

        if ($doFlush) {
            $this->em->flush();
        }
    }

    /**
     * Delete the given entity
     *
     * @param object $entity An entity instance
     */
    public function remove($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    /**
     * Returns entity repository
     *
     * @return \Doctrine\ORM\EntityRepository|EntityRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }
}
