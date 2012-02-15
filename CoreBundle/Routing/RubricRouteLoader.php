<?php
/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Iphp\CoreBundle\Routing;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Config\Loader\LoaderResolverInterface;



/*
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Config\FileLocator;


use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

use Symfony\Component\Yaml\Yaml;
*/

class RubricRouteLoader implements LoaderInterface
{

    /**
     * \Symfony\Component\HttpKernel\Kernel
     */
    protected $kernel;

    /**
     * \Doctrine\ORM\EntityManager
     */
    protected $em;

    protected $container;

    /**
     * \Iphp\CoreBundle\Module\ModuleFactory
     */
    protected $moduleFactory;

    public function __construct(
        \Symfony\Component\HttpKernel\Kernel $kernel,
        \Doctrine\ORM\EntityManager $em,
        $container)
    {
        $this->kernel = $kernel;
        $this->em = $em;
        $this->container = $container;

        $this->moduleFactory = new  \Iphp\CoreBundle\Module\ModuleFactory();
    }


    /**
     * @param string $resource
     * @param null $type
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        if ($type == 'iphp_rubric') {
            return true;
        }

        return false;
    }

    /**
     * @param string $resource
     * @param null $type
     * @return \Symfony\Component\Routing\RouteCollection
     */
    public function load($resource, $type = null)
    {
        $routes = new RouteCollection();

        //print 'Грузим роуты';

        $logger = $this->container->get ('logger');

        $logger->info('Загрузка рубрик для построения роутинга');


        $a = microtime(true);

        $rubrics = $this->em->getRepository('ApplicationIphpCoreBundle:Rubric')
                ->createQueryBuilder('r')
                ->orderBy('r.level', 'DESC')
                ->getQuery()->getResult();


        /*        $pattern = '/catalog/';
      $defaults = array(            '_controller' => 'InformikaCatalogBundle:Navigation:chose',        );
      $route = new Route($pattern, $defaults);
      $routes->add('InformikaCatalogBundle_navigation', $route);

       return $routes;*/

        foreach ($rubrics as $rubric)
        {
          //  print '<br>' . $rubric->getFullPath();


            $controller = $rubric->getControllerName();

          //  print ':' . $controller;


            $rubricRoutes = null;
            //В контроллере можеть быть:
            // Название бандла - пытаемся использовать роутинг из бандла: файд routing.xml или из контроллеров
            if ($controller) {

                if (substr($controller, -6) == 'Bundle') {
                    $rubricRoutes = $this->loadFromBundle($controller);
                }
                if (substr($controller, -6) == 'Module') {


                    $module = $this->moduleFactory->getModuleFromRubric ($rubric);

                    if ($module)
                    $rubricRoutes = $module->getRoutes();
                }


            }


            if ($rubricRoutes) {
             //   print 'Префикс для коллекции:' . substr($rubric->getFullPath(), 0, -1);
                foreach ($rubricRoutes as $route)
                {
                    // print_r ($route->getDefaults());
                    $route->setDefault('_rubric', $rubric->getFullPath());
                }
                $routes->addCollection($rubricRoutes, substr($rubric->getFullPath(), 0, -1));
            }
        }


        /*$pattern = '/extra';
        $defaults = array(            '_controller' => 'AcmeRoutingBundle:Demo:extraRoute',        );
        $route = new Route($pattern, $defaults);
        $routes->add('extraRoute', $route);*/


        /* foreach ($this->adminServiceIds as $id) {

           $admin = $this->pool->getInstance($id);

           foreach ($admin->getRoutes()->getElements() as $code => $route) {
               $collection->add($route->getDefault('_sonata_name'), $route);
           }

           $reflection = new \ReflectionObject($admin);
           $collection->addResource(new FileResource($reflection->getFileName()));
       }

       $reflection = new \ReflectionObject($this->container);
       $collection->addResource(new FileResource($reflection->getFileName()));*/


        $b = microtime(true)-$a;

        $logger->info('Загрузили роуты за'.$b.' с');

        return $routes;
    }


    public function getResolver()
    {
    }

    public function setResolver(LoaderResolverInterface $resolver)
    { // irrelevant to us, since we don't need a resolver
    }





    function loadFromBundle($bundleName)
    {
        $bundleRoutes = null;
        $bundle = $this->kernel->getBundle($bundleName);

        if ($bundle) {
            //  var_dump ($bundle);

            $bundleDir = $bundle->getPath();


            //Пробуем Resources/config/routing.yml

            $ymlConfigFile = $bundleDir . '/Resources/config/routing.yml';
            if (file_exists($ymlConfigFile)) {
                //print 'Есть routing.yml!';


                //$bundleRoutes = new \Symfony\Component\Routing\Loader\YamlFileLoader ();
                // $bundleRoutes = Yaml::parse( $ymlConfigFile);

                $loader = $this->container->get('routing.loader');
                $bundleRoutes = $loader->load($ymlConfigFile);


                //  var_dump($bundleRoutes);
            }

        }
        else
        {
            die ('Нет бандла ' . $bundleName);
        }

        return $bundleRoutes;
    }


}