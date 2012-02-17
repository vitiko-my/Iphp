<?
namespace Iphp\TreeBundle\Tree\Strategy;

use Gedmo\Tree\Strategy;
use Gedmo\Tree\Strategy\ORM\Nested as BaseNested;
use Doctrine\ORM\EntityManager;
use Gedmo\Tool\Wrapper\AbstractWrapper;
use Doctrine\ORM\QueryBuilder;

class Nested extends BaseNested implements Strategy
{

    //protected $nodePositions;
    /**
     * Set node position strategy
     *
     * @param string $oid
     * @param string $position
     */
    /*
    public function setNodePosition($oid, $position)
    {
        $valid = array(
            self::FIRST_CHILD,
            self::LAST_CHILD,
            self::NEXT_SIBLING,
            self::PREV_SIBLING
        );
        if (!in_array($position, $valid, false)) {
            throw new \Gedmo\Exception\InvalidArgumentException("Position: {$position} is not valid in nested set tree");
        }
        $this->nodePositions[$oid] = $position;
    }
*/

    public function processScheduledInsertion($em, $node)
    {
        parent::processScheduledInsertion($em, $node);
        $meta = $em->getClassMetadata(get_class($node));

        //  $meta->getReflectionProperty('path')->setValue ($node,'/');
        $meta->getReflectionProperty('fullPath')->setValue($node, '/');
    }


    /**
     * Update path of node
     * @param object $node - target node
     * @param object $parent - destination node
     */

    protected function setPathData(QueryBuilder $qb, $node, $parent)
    {
        $nodeFullPath = $parent->getPropertyValue('fullPath') . $node->getPropertyValue('path') . '/';
        $qb->set('node.fullPath', $qb->expr()->literal($nodeFullPath));
    }


    /**
     * При обновлении рубрики если изменилмся ее путь но не изменились другие данные - обновить fullPath
     * @param  $em
     * @param  $node
     * @return void
     */
    public function processScheduledUpdate($em, $node)
    {
        parent::processScheduledUpdate($em, $node);


        $meta = $em->getClassMetadata(get_class($node));
        $config = $this->listener->getConfiguration($em, $meta->name);
        $uow = $em->getUnitOfWork();
        $changeSet = $uow->getEntityChangeSet($node);

       // var_dump($changeSet);


        if (isset($changeSet['path']) && !(
                isset($changeSet[$config['left']]) || isset($changeSet[$config['parent']]))
        ) {
            $parent = (isset($changeSet[$config['parent']])) ? $changeSet[$config['parent']][1] : $node->getParent();
            $meta->getReflectionProperty('fullPath')->setValue($node, $parent->getFullPath() . $changeSet['path'][1] . '/');
        }
    }

    /**
     * Update the $node with a diferent $parent
     * destination
     *
     * @param EntityManager $em
     * @param object $node - target node
     * @param object $parent - destination node
     * @param string $position
     * @throws Gedmo\Exception\UnexpectedValueException
     * @return void
     */
    public function updateNode(EntityManager $em, $node, $parent, $position = 'FirstChild')
    {

        // die (1);
        $wrapped = AbstractWrapper::wrap($node, $em);
        $meta = $wrapped->getMetadata();
        $config = $this->listener->getConfiguration($em, $meta->name);


        $rootId = isset($config['root']) ? $wrapped->getPropertyValue($config['root']) : null;
        $identifierField = $meta->getSingleIdentifierFieldName();
        $nodeId = $wrapped->getIdentifier();

        $left = $wrapped->getPropertyValue($config['left']);
        $right = $wrapped->getPropertyValue($config['right']);

        $isNewNode = empty($left) && empty($right);
        if ($isNewNode) {
            $left = 1;
            $right = 2;
        }

     //   var_dump($node);
    //    var_dump($isNewNode);


        $oid = spl_object_hash($node);
        if (isset($this->nodePositions[$oid])) {
            $position = $this->nodePositions[$oid];
        }

      //  print "\n".$position." ".$node->getTitle()."(".$oid.")";
        $level = 0;
        $treeSize = $right - $left + 1;
        $newRootId = null;
        if ($parent) {
            $wrappedParent = AbstractWrapper::wrap($parent, $em);

            $parentRootId = isset($config['root']) ? $wrappedParent->getPropertyValue($config['root']) : null;
            $parentLeft = $wrappedParent->getPropertyValue($config['left']);
            $parentRight = $wrappedParent->getPropertyValue($config['right']);
            if (!$isNewNode && $rootId === $parentRootId && $parentLeft >= $left && $parentRight <= $right) {
                throw new UnexpectedValueException("Cannot set child as parent to node: {$nodeId}");
            }
            if (isset($config['level'])) {
                $level = $wrappedParent->getPropertyValue($config['level']);
            }


            switch ($position) {

                case self::PREV_SIBLING:
                    $newParent = $wrappedParent->getPropertyValue($config['parent']);
                    if (is_null($newParent) && (isset($config['root']) || $isNewNode)) {
                        throw new UnexpectedValueException("Cannot persist sibling for a root node, tree operation is not possible");
                    }
                    $wrapped->setPropertyValue($config['parent'], $newParent);
                    $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $node);
                    $start = $parentLeft;
                    break;

                case self::NEXT_SIBLING:
                    $newParent = $wrappedParent->getPropertyValue($config['parent']);
                    if (is_null($newParent) && (isset($config['root']) || $isNewNode)) {
                        throw new UnexpectedValueException("Cannot persist sibling for a root node, tree operation is not possible");
                    }
                    $wrapped->setPropertyValue($config['parent'], $newParent);
                    $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $node);
                    $start = $parentRight + 1;
                    break;

                case self::LAST_CHILD:
                    $start = $parentRight;
                    $level++;
                    break;

                case self::FIRST_CHILD:
                default:
                    $start = $parentLeft + 1;
                    $level++;
                    break;
            }
            $this->shiftRL($em, $config['useObjectClass'], $start, $treeSize, $parentRootId);
            if (!$isNewNode && $rootId === $parentRootId && $left >= $start) {
                $left += $treeSize;
                $wrapped->setPropertyValue($config['left'], $left);
            }
            if (!$isNewNode && $rootId === $parentRootId && $right >= $start) {
                $right += $treeSize;
                $wrapped->setPropertyValue($config['right'], $right);
            }
            $newRootId = $parentRootId;
        } elseif (!isset($config['root'])) {
            $start = isset($this->treeEdges[$meta->name]) ?
                    $this->treeEdges[$meta->name] : $this->max($em, $config['useObjectClass']);
            $this->treeEdges[$meta->name] = $start + 2;
            $start++;
        } else {
            $start = 1;
            $newRootId = $nodeId;
        }


        // die ('Update Node');

        $diff = $start - $left;
        if (!$isNewNode) {
            $levelDiff = isset($config['level']) ? $level - $wrapped->getPropertyValue($config['level']) : null;
            $this->shiftRangeRL(
                $em,
                $config['useObjectClass'],
                $left,
                $right,
                $diff,
                $rootId,
                $newRootId,
                $levelDiff
            );
            $this->shiftRL($em, $config['useObjectClass'], $left, -$treeSize, $rootId);

        } else {


            $qb = $em->createQueryBuilder();
            $qb->update($config['useObjectClass'], 'node');
            if (isset($config['root'])) {
                $qb->set('node.' . $config['root'], $newRootId);
                $wrapped->setPropertyValue($config['root'], $newRootId);
                $em->getUnitOfWork()->setOriginalEntityProperty($oid, $config['root'], $newRootId);
            }
            if (isset($config['level'])) {
                $qb->set('node.' . $config['level'], $level);
                $wrapped->setPropertyValue($config['level'], $level);
                $em->getUnitOfWork()->setOriginalEntityProperty($oid, $config['level'], $level);
            }
            if (isset($newParent)) {
                $wrappedNewParent = AbstractWrapper::wrap($newParent, $em);
                $newParentId = $wrappedNewParent->getIdentifier();
                $qb->set('node.' . $config['parent'], $newParentId);
                $wrapped->setPropertyValue($config['parent'], $newParent);
                $em->getUnitOfWork()->setOriginalEntityProperty($oid, $config['parent'], $newParent);
            }



            if (isset($wrappedParent))
            $this->setPathData($qb, $wrapped, isset($newParent) ? $newParent : $wrappedParent);

            $qb->set('node.' . $config['left'], $left + $diff);
            $qb->set('node.' . $config['right'], $right + $diff);
            $qb->where("node.{$identifierField} = {$nodeId}");

            //var_dump ($qb->getDQL());
            //exit();
            $qb->getQuery()->getSingleScalarResult();
            $wrapped->setPropertyValue($config['left'], $left + $diff);
            $wrapped->setPropertyValue($config['right'], $right + $diff);
            $em->getUnitOfWork()->setOriginalEntityProperty($oid, $config['left'], $left + $diff);
            $em->getUnitOfWork()->setOriginalEntityProperty($oid, $config['right'], $right + $diff);
        }
    }

}