<?php

namespace Iphp\TreeBundle\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository as GedmoNestedTreeRepository,
Gedmo\Tool\Wrapper\EntityWrapper,
Doctrine\ORM\Query,
Gedmo\Tree\Strategy,
Gedmo\Tree\Strategy\ORM\Nested,
Gedmo\Exception\InvalidArgumentException,
Doctrine\ORM\Proxy\Proxy;

/**
 * @author Nosov Victor
 */
class NestedTreeRepository extends GedmoNestedTreeRepository
{




    function getTreeRecordset( $prepareQueryBuilder = null)
    {
        $qb = $this->createQueryBuilder('r');

        if ($prepareQueryBuilder) $prepareQueryBuilder($qb);


        return new \Iphp\TreeBundle\Model\TreeNodeIterator($qb->getQuery()->getResult());

        /*$repository->createQueryBuilder('r')
                        ->andWhere('r.level > 0')
                        ->orderBy('r.left', 'ASC')
                        ->getQuery();*/
    }

}
