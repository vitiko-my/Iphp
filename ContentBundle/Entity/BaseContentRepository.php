<?php

namespace Iphp\ContentBundle\Entity;

use Doctrine\ORM\EntityRepository;


class BaseContentRepository extends EntityRepository
{
    public function rubricIndex($rubric)
    {
        return $this->createQuery('c', function ($qb) use ($rubric)
        {
            $qb->fromRubric($rubric)->setMaxResults(1);
        })->getOneOrNullResult();
    }


    /**
     * @param string $alias
     * @return \Iphp\ContentBundle\Entity\ContentQueryBuilder
     */
    public function createQueryBuilder($alias = 'c', \Closure $prepareQueryBuidler = null)
    {
        $qb = new ContentQueryBuilder($this->_em);
        $qb->select($alias)->from($this->_entityName, $alias);

        if ($prepareQueryBuidler) $prepareQueryBuidler($qb);
        return $qb;
    }


    /**
     * @param Closure $prepareQueryBuidler
     * @param string $alias
     * @return \Doctrine\ORM\Query
     */
    public function createQuery($alias = 'c', \Closure $prepareQueryBuidler = null)
    {
        return $this->createQueryBuilder($alias,$prepareQueryBuidler)->getQuery();
    }


}