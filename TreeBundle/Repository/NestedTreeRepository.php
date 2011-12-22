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


}
