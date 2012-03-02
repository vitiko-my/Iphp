<?php
/**
 * Created by Vitiko
 * Date: 25.01.12
 * Time: 15:29
 */

namespace Iphp\ContentBundle\Module;

use Iphp\CoreBundle\Module\Module;


/**
 * Модуль - материал в индексе рубрики
 */
class ContentListModule extends Module
{

    function __construct()
    {
        $this->setName('Материалы - список');
        $this->allowMultiple = true;
    }

    protected function registerRoutes()
    {
        $this->addRoute('index', '/', array('_controller' => 'IphpContentBundle:Content:list'))
             ->addRoute('contentById', '/{id}/', array('_controller' => 'IphpContentBundle:Content:contentById'));
    }

}
