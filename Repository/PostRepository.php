<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class PostRepository.
 *
 * This is the repository of the Post entity
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class PostRepository extends EntityRepository
{
    /**
     * @param \DateTime|null $date
     * 
     * @return array
     * 
     * @author Elvis Morales <elvismdev@gmail.com> & Leroy Ley <lele140686@gmail.com>
     */
    public function findByDate(\DateTime $date = null)
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.date LIKE :date')
            ->addOrderBy('p.date', 'DESC')
            ->addOrderBy('p.id', 'DESC')
            ->setParameter('date', $date->format('Y-m-d').'%');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param string $category
     *
     * @return array
     *
     * @author Guillaume Leclercq <g.leclercq12@gmail.com>
     */
    public function findByCategory($category)
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('Ekino\WordpressBundle\Entity\TermRelationships', 'tr', 'WITH', 'p.id = tr.post')
            ->leftJoin('Ekino\WordpressBundle\Entity\Term', 't', 'WITH', 't.id = tr.taxonomy')
            ->where('t.name = :category')
            ->setParameter('category', $category)
            ->getQuery()->getResult();
    }
}
