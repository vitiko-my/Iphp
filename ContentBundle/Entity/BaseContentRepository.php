<?php

namespace Iphp\ContentBundle\Entity;

use Doctrine\ORM\EntityRepository;


class BaseContentRepository extends EntityRepository
{
    function rubricIndex($rubric)
    {
        return $this->createQueryBuilder('c')

                ->where('c.rubric = ?1')
                ->setMaxResults(1)
                ->setParameter(1, $rubric->getId())

                ->getQuery()->getOneOrNullResult();
    }


    /**
     * @param string $alias
     * @return \Iphp\ContentBundle\Entity\ContentQueryBuilder
     */
    function createQueryBuilder($alias = 'c')
    {
        $qb = new ContentQueryBuilder($this->_em);

        $qb->select($alias)
                ->from($this->_entityName, $alias);

        return $qb;
    }


    function findByRubricAndSlug($rubric, $slug)
    {

       // print $this->createQueryBuilder('c')->fromRubric($rubric)->whereSlug($slug)
       //                 ->getQuery()->getSql();
        return $this->createQueryBuilder('c')->fromRubric($rubric)->whereSlug($slug)
                ->getQuery()->getOneOrNullResult();
    }
}


