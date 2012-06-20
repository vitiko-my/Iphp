<?php
/**
 * Created by Vitiko
 * Date: 25.01.12
 * Time: 15:29
 */

namespace Iphp\CoreBundle\Module;

use Iphp\CoreBundle\Module\Module;


/**
 * Модуль индекс сайта
 */
class SiteIndexModule extends Module
{

    function __construct()
    {
        $this->setName('Индекс сайта');
        $this->allowMultiple = false;
    }

    protected function registerRoutes()
    {
        $this->addRoute('site_index', '/', array('_controller' => 'ApplicationIphpCoreBundle:Rubric:indexSite'));
        //    ->addRoute('contentById','/{id}/', array('_controller' => 'IphpContentBundle:Content:contentById'));
    }

}
