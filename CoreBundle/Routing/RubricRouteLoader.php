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

use Symfony\Component\HttpKernel\Kernel;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

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

    public function __construct(Kernel $kernel, EntityManager $em, ContainerInterface $container)
    {
        $this->kernel = $kernel;
        $this->em = $em;
        $this->container = $container;

        $this->moduleManager = $container->get('iphp.core.module.manager');
    }


    /**
     * @param string $resource
     * @param null $type
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        return ($type == 'iphp_rubric');
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

        $logger = $this->container->get('logger');
        $logger->info('Загрузка рубрик для построения роутинга');

        $a = microtime(true);
        $rubrics = $this->em->getRepository('ApplicationIphpCoreBundle:Rubric')
                ->createQueryBuilder('r')
                ->orderBy('r.level', 'DESC')
                ->getQuery()->getResult();


        foreach ($rubrics as $rubric) {
            $controller = $rubric->getControllerName();
            $rubricRoutes = null;

            //В контроллере можеть быть: Класс модуля
            if ($controller && substr($controller, -6) == 'Module') {
                $module = $this->moduleManager->getModuleFromRubric($rubric);
                //print '--'.$rubric.' '.get_class ($module);
                if ($module) $rubricRoutes = $module->getRoutes();
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


        $b = microtime(true) - $a;

        $logger->info('Загрузили роуты за' . $b . ' с');

        return $routes;
    }


    public
    function getResolver()
    {
    }

    public
    function setResolver(LoaderResolverInterface $resolver)
    { // irrelevant to us, since we don't need a resolver
    }


}