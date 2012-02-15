<?php
/**
 * Created by Vitiko
 * Date: 25.01.12
 * Time: 15:29
 */

namespace Iphp\ContentBundle\Module;

use Iphp\CoreBundle\Module\Module;
use Symfony\Component\Routing\Route;

/**
 * Модуль - материал в индексе рубрики
 */
class ContentIndexModule extends Module
{


    protected function initialization()
    {
        $this->setName('Контент - индекс рубрики');
    }

    protected function registerRoutes()
    {
        $this->addRoute(new Route('/', array('_controller' => 'IphpContentBundle:Content:index')), 'index')
             ->addRoute(new Route('/{id}/', array('_controller' => 'IphpContentBundle:Content:contentById')), 'contentById');
    }

}
