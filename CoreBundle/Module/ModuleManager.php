<?php
/**
 * Created by Vitiko
 * Date: 02.02.12
 * Time: 13:18
 */


namespace Iphp\CoreBundle\Module;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;


use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Finder\Finder;

class ModuleManager  extends ContainerAware
{

    function __construct( ContainerInterface $container)
    {
          $this->setContainer($container);
    }

    protected $modulesPath = 'Module';

    function modules()
    {
        $modules = array();
        foreach ($this->container->get ('kernel')->getBundles() as $bundle)
        $modules = array_merge($modules, $this->bundleModules($bundle));

        return $modules;
    }

    function loadRoutes ($resource, $type = null)
    {
     return $this->getRoutingLoader()->load ($resource, $type);
    }

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Routing\DelegatingLoader
     */
    function getRoutingLoader()
    {
        return $this->container->get ('routing.loader');
    }

    function bundleModuleDir($bundle)
    {
        return $bundle->getPath() . DIRECTORY_SEPARATOR . $this->modulesPath;
    }


    function bundleModules($bundle)
    {
        $dir = $this->bundleModuleDir($bundle);
        if (!file_exists($dir) || !is_dir($dir) || !is_readable($dir)) return array();

        $finder = new Finder();

        $modules = array();
        foreach ($finder->files()->in($dir)->name('/.+Module\.php$/') as $file) {
            $moduleClass = $bundle->getNamespace() . '\\' . $this->modulesPath . '\\' .
                    substr($file->getRealpath(), strlen($dir) + 1, -4);

            if (class_exists($moduleClass)) {
                $modules[] = new $moduleClass();
            }
        }
        return $modules;
    }


    /**
     * @param \Application\Iphp\CoreBundle\Entity\Rubric $rubric
     * @return \Iphp\CoreBundle\Module\Module
     */
    function getModuleFromRubric(\Application\Iphp\CoreBundle\Entity\Rubric $rubric)
    {
        $moduleClassName = $rubric->getControllerName();
        if (!$moduleClassName) return null;

        $module = $this->getModuleInstance($moduleClassName );
        if (!$module) return null;

        return $module->setRubric ($rubric);
    }

    function getModuleInstance($moduleClassName)
    {
       if (!class_exists($moduleClassName, true)) return null;
       $module =  new $moduleClassName ();
       $module->setManager ($this);
       return $module;
    }
}
