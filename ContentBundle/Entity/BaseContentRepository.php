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
}
