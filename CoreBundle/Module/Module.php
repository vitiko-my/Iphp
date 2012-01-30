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


    public function __construct()
    {
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
      $this->routeCollection->add ($name, $route);
      return $this;
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
