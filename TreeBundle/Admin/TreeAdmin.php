<?php
namespace Iphp\TreeBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Route\RouteCollection;
use Iphp\TreeBundle\Model\TreeNodeIterator;


class TreeAdmin extends Admin
{



    function getListTemplate()
    {
        return 'IphpTreeBundle:CRUD:tree.html.twig';
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('chpos', '{id}/chpos/{parent}/{after}',
            array('_controller' => 'IphpTreeBundle:CRUD:changePosition'));

    }


    public function changePosition($node, $parent, $after)
    {
        $this->preUpdate($node);
        $this->getModelManager()->changePosition($node, $parent, $after);
        $this->postUpdate($node);
    }






    public function getTreeIterator()
    {
        $qb = $this->getDatagrid()->getQuery()->getQueryBuilder();

        $qb->orderBy('o.left');
       //$proxyQuery->setSortBy('left');

      //  print    $qb->getQuery()->getResult();
       return new TreeNodeIterator($qb->getQuery()->getResult());
    }



}