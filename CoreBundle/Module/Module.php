<?php
/**
 * Created by Vitiko
 * Date: 25.01.12
 * Time: 15:29
 */

namespace Iphp\CoreBundle\Module;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

use Iphp\CoreBundle\Module\ModuleManager;

/**
 * Модуль, размещаемый в разделе сайта
 */
abstract class Module
{

    /**
     * Название модуля
     * @var string
     */
    protected $name;



    /**
     * @var Iphp\CoreBundle\Module\ModuleManager
     */
    protected $moduleManager;



    /**
     * Коллекция роутов для модуля
     * @var Symfony\Component\Routing\RouteCollection
     */
    protected $routeCollection = null;




    protected $rubric = null;

    abstract protected function registerRoutes();


    function buildRouteCollection()
    {
        if ($this->routeCollection) return;

        $this->routeCollection = new RouteCollection();
        $this->registerRoutes();
    }


    public function setManager (ModuleManager $moduleManager)
    {
        $this->moduleManager = $moduleManager;
        return $this;
    }

    public function setRubric(\Application\Iphp\CoreBundle\Entity\Rubric $rubric)
    {
        $this->rubric = $rubric;
        return $this;
    }




    protected function importRoutes ($resource)
    {
      foreach ($this->moduleManager->loadRoutes ($resource) as $name => $route)
      $this->routeCollection->add($name, $route);
    }

    protected function suggestRouteName(Route $route)
    {
      $controller = $route->getDefault('_controller');

      $pattern = $route->getPattern();
      if ($pattern == '/') return 'index';
    }



    protected function addRoute($name, $pattern, array $defaults = array(), array $requirements = array(),
                                array $options = array())
    {
        //print '<br>--'.$this->prepareRubricPath($this->rubric->getFullPath()).'_'.$name;

        $route = new Route ($pattern,$defaults,$requirements,$options);
        //$name = $name ? $name : $this->suggestRouteName($route);
        $this->routeCollection->add (
            $this->rubric ? $this->prepareRubricPath($this->rubric->getFullPath()) . '_' . $name : $name,
            $route);
        return $this;
    }


    protected function prepareRubricPath($path)
    {
        return str_replace(array('/', '-'), '_', substr($path, 1, -1));
    }

    public function getRoutes()
    {
        $this->buildRouteCollection();
        return $this->routeCollection;
    }


    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }
}
