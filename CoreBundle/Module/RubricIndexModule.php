<?php
/**
 * Created by Vitiko
 * Date: 25.01.12
 * Time: 15:29
 */

namespace Iphp\CoreBundle\Module;

use Iphp\CoreBundle\Module\Module;


/**
 * Модуль - материал в индексе рубрики
 */
class RubricIndexModule extends Module
{

    function __construct()
    {
        $this->setName('Рубрика - список подрубрик');
    }

    protected function registerRoutes()
    {
        $this->addRoute('index', '/', array('_controller' => 'IphpCoreBundle:Rubric:indexSubrubrics'));
    }

}
