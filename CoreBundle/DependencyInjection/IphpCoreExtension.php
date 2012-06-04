<?php



namespace Iphp\CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;

//use Symfony\Component\DependencyInjection\Definition;

use Sonata\EasyExtendsBundle\Mapper\DoctrineCollector;

class IphpCoreExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);


        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));


        $loader->load('twig.xml');
        $loader->load('services.xml');

        if ($config['separate_admin_env']) {
            if (array_key_exists ('SonataAdminBundle', $container->getParameter('kernel.bundles'))) $loader->load('admin.xml');
            else   $loader->load('front.xml');
        }
        else
        {
            $loader->load('admin.xml');
            $loader->load('front.xml');
        }
        $loader->load('block.xml');
        $this->registerDoctrineMapping($config);

        //  $loader->load('twig.xml');
        //  $loader->load('form.xml');

        //  $blog = new Definition('Sonata\NewsBundle\Model\Blog', array($config['title'], $config['link'], $config['description']));
        //  $container->setDefinition('sonata.news.blog', $blog);
    }


    /**
     * @param array $config
     * @return void
     */
    public function registerDoctrineMapping(array $config)
    {
        // print 'Extends!';

        if (!class_exists($config['class']['rubric'])) {
            return;
        }

        $collector = DoctrineCollector::getInstance();

        $collector->addAssociation($config['class']['rubric'], 'mapOneToMany', array(
            'fieldName' => 'blocks',
            'targetEntity' => $config['class']['block'],
            'cascade' => array(
                'remove',
                'persist',
                'refresh',
                'merge',
                'detach',
            ),
            'mappedBy' => 'rubric',
            'orphanRemoval' => false,
            'orderBy' => array(
                'position' => 'ASC',
            ),
        ));


        $collector->addAssociation($config['class']['block'], 'mapOneToMany', array(
            'fieldName' => 'children',
            'targetEntity' => $config['class']['block'],
            'cascade' => array(
                'remove',
                'persist',
            ),
            'mappedBy' => 'parent',
            'orphanRemoval' => true,
            'orderBy' => array(
                'position' => 'ASC',
            ),
        ));

        $collector->addAssociation($config['class']['block'], 'mapManyToOne', array(
            'fieldName' => 'parent',
            'targetEntity' => $config['class']['block'],
            'cascade' => array(
            ),
            'mappedBy' => NULL,
            'inversedBy' => NULL,
            'joinColumns' => array(
                array(
                    'name' => 'parent_id',
                    'referencedColumnName' => 'id',
                    'onDelete' => 'CASCADE',
                ),
            ),
            'orphanRemoval' => false,
        ));

        $collector->addAssociation($config['class']['block'], 'mapManyToOne', array(
            'fieldName' => 'rubric',
            'targetEntity' => $config['class']['rubric'],
            'cascade' => array(
                'persist',
            ),
            'mappedBy' => NULL,
            'inversedBy' => NULL,
            'joinColumns' => array(
                array(
                    'name' => 'rubric_id',
                    'referencedColumnName' => 'id',
                    'onDelete' => 'CASCADE',
                ),
            ),
            'orphanRemoval' => false,
        ));
    }
}