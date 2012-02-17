<?php
namespace Iphp\TreeBundle\Admin;

use \Sonata\AdminBundle\Exception\ModelManagerException;

class ModelManager extends \Sonata\DoctrineORMAdminBundle\Model\ModelManager
{


    function changePosition($node, $parent, $after)
    {
        $changeParent = $node->getParentId() != $parent;

        $entityManager = $this->getEntityManager($node);
        $repository = $entityManager->getRepository(get_class($node));


        if (!$changeParent)
        {
            if ($after == 0) $repository->persistAsFirstChild($node);
            else
            {
                $afterNode = $this->find(get_class($node), $after);



          /*      print 'Set'.$node->getTitle().' (parent: '.$node->getParentId().') after '.
                         $afterNode->getTitle().' (parent: '.$afterNode->getParentId().') ';*/


                if (!$afterNode )   throw new ModelManagerException ('Не найден узел c id='.$after);

                if ($afterNode->getParentId() != $node->getParentId())
                    throw new ModelManagerException ('Изменение родителя пока не реализовано');
                $repository->persistAsNextSiblingOf($node, $afterNode);
            }
            $entityManager->flush();
        }
        else
        {
            throw new ModelManagerException ('Изменение родителя пока не реализовано');
        }

    }


    /*   function createQuery()
    {

    }*/
}
