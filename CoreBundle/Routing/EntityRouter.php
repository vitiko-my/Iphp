<?php

namespace Iphp\CoreBundle\Routing;

use Iphp\CoreBundle\Model\Rubric;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
/**
 * Created by Vitiko
 * Date: 02.08.12
 * Time: 10:26
 */

class EntityRouter
{

    protected $router;

    public function __construct(Router $router)
    {
      $this->router = $router;
    }



    public function generateEntityActionPath ($entity, $action = 'view', Rubric $rubric = null)
    {

       // print get_class ($this->router);

       // $this->generate($name
       // print get_class ($entity);



       return $this->router->generate (
           $this->routeNameForEntityAction ($entity, $action),
           array ('id' => $entity->getId())

       );


    }

    public function routeNameForEntityAction ($entity, $action,Rubric $rubric = null)
    {

        if (is_object($entity))
        {
         $entityPart = str_replace ('\\','',str_replace ('Entity\\','',get_class ($entity)));
        }
        else
        {
          //Todo: Хак, нужно использовать kernel->getBundle(..)->getNamespace() но доступа к kernel пока нет
          //list ($bundleName, $entityName) = explode (':',$entity);

          $entityPart  = str_replace (':','',$entity);
        }

        return $entityPart .'_'.lcfirst($action);
    }
}
