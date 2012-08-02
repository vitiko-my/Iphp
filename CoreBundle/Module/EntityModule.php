<?php
/**
 * Created by Vitiko
 * Date: 02.08.12
 * Time: 11:55
 */

namespace Iphp\CoreBundle\Module;

class EntityModule extends Module
{

    protected $entityName;

    protected $controllerName;

    protected $entityActions = array(
        'index' => '/',
        'view' => '/{id}/'
    );

    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
        return $this;
    }

    public function getEntityName()
    {
        return $this->entityName;
    }

    protected function registerRoutes()
    {

        // list ($bundleName, $entityName) = explode (':',  $this->entityName);
        //  if (!$bundleName) return false;


        foreach ($this->entityActions as $action => $pattern)
        {
            $routeName = $this->moduleManager->getEntityRouter()->routeNameForEntityAction($this->entityName, $action);
            $controllerName =  $this->entityName.':'.$action;
            //print '-' . $routeName;

            $this->addRoute($routeName, $pattern, array('_controller' =>    $controllerName ));
        }
        //forea ()

        //exit();
    }

    public function setEntityActions($entityActions)
    {
        $this->entityActions = $entityActions;
        return $this;
    }

    public function getEntityActions()
    {
        return $this->entityActions;
    }
}