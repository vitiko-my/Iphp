<?php
/**
 * Created by Vitiko
 * Date: 02.02.12
 * Time: 13:18
 */


namespace Iphp\CoreBundle\Module;

class ModuleFactory {


    function getModuleFromRubric (\Application\Iphp\CoreBundle\Entity\Rubric $rubric)
    {
        $moduleClassName = $rubric->getControllerName();

        if (!class_exists($moduleClassName, true)) return null;

        //print 'Есть класс ' . $moduleClassName;

        $module = new $moduleClassName ($rubric);

        return $module;

    }
}
