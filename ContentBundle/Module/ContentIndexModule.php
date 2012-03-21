<?php
/**
 * Created by Vitiko
 * Date: 25.01.12
 * Time: 15:29
 */

namespace Iphp\ContentBundle\Module;

use Iphp\CoreBundle\Module\Module;
use Iphp\ContentBundle\Admin\Extension\RubricAdminExtension;

/**
 * Модуль - материал в индексе рубрики
 */
class ContentIndexModule extends Module
{

    function __construct()
    {
        $this->setName('Материал - индекс рубрики');
        $this->allowMultiple = true;
    }

    protected function registerRoutes()
    {
        $this->addRoute('index', '/', array('_controller' => 'IphpContentBundle:Content:index'));
        //    ->addRoute('contentById','/{id}/', array('_controller' => 'IphpContentBundle:Content:contentById'));
    }

    function getAdminExtension()
    {
        return new RubricAdminExtension;
    }

}
