<?php
/**
 * Created by Vitiko
 * Date: 25.01.12
 * Time: 15:29
 */

namespace Iphp\CoreBundle\Module;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

/**
 * Модуль, размещаемый в разделе сайта
 */
abstract class Module
{


    /**
     * Коллекция роутов для модуля
     * @var
     */
    protected $routeCollection;


    /**
     * Название модуля
     * @var
     */
    protected $name;



    protected $rubric;

    public function __construct(\Application\Iphp\CoreBundle\Entity\Rubric $rubric = null)
    {
        $this->rubric = $rubric;
        $this->routeCollection = new RouteCollection();

        $this->initialization();
        $this->registerRoutes();

    }


    abstract protected function registerRoutes();
    protected function registerRoutesFromController($controllerName) {}


    protected function initialization()
    {

    }

    protected function addRoute(Route $route, $name = '')
    {
      //print '<br>--'.$this->prepareRubricPath($this->rubric->getFullPath()).'_'.$name;
      $this->routeCollection->add (
          $this->rubric ?  $this->prepareRubricPath($this->rubric->getFullPath()).'_'.$name : $name, $route);
      return $this;
    }


    protected function prepareRubricPath($path)
    {
        return str_replace (array ('/','-'),'_',substr ($path,1,-1));
    }

    public function getRoutes()
    {
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
