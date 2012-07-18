<?php

namespace Iphp\ContentBundle\Entity;


use Iphp\CoreBundle\Entity\BaseEntityRepository;

class BaseContentRepository extends BaseEntityRepository
{
    public function rubricIndex($rubric)
    {
        return $this->createQuery('c', function ($qb) use ($rubric)
        {
            $qb->fromRubric($rubric)->setMaxResults(1);
        })->getOneOrNullResult();
    }

    protected function getDefaultQueryBuilder(\Doctrine\ORM\EntityManager $em)
    {
        return new ContentQueryBuilder($em);
    }

}