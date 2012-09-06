<?php



namespace Iphp\ContentBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;

//use Symfony\Component\DependencyInjection\Definition;
use Sonata\EasyExtendsBundle\Mapper\DoctrineCollector;

class IphpContentExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);


        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        if (array_key_exists('SonataAdminBundle', $container->getParameter('kernel.bundles'))) {
            $loader->load('admin.xml');
        }

        $loader->load('block.xml');


        $this->registerDoctrineMapping($config);

        //$container->getDefinition('iphp.')

        //  $loader->load('orm.xml');
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
        $collector = DoctrineCollector::getInstance();

        if (class_exists($config['class']['rubric'])) {

            //Связь рубрика - материалы ( getContents() )
            $collector->addAssociation($config['class']['rubric'], 'mapOneToMany', array(
                'fieldName' => 'contents',
                'targetEntity' => $config['class']['content'],
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
                    'id' => 'ASC',
                ),

            ));


            //Связь материал - рубрика
            $collector->addAssociation($config['class']['content'], 'mapManyToOne', array(
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
                        'onDelete' => 'SET NULL',
                    ),
                ),
                'orphanRemoval' => false,
            ));
        }

/*
        if (class_exists($config['class']['media']) && $config['class']['media']) {

            $collector->addAssociation($config['class']['content'], 'mapManyToOne', array(
                'fieldName' => 'image',
                'targetEntity' => $config['class']['media'],
                'cascade' => array(
                    'persist',
                ),
                'mappedBy' => NULL,
                'inversedBy' => NULL,
                'joinColumns' => array(
                    array(
                        'name' => 'image_id',
                        'referencedColumnName' => 'id',
                        'onDelete' => 'SET NULL',
                    ),
                ),
                'orphanRemoval' => false,
            ));

        }*/


        if (class_exists($config['class']['author']) && $config['class']['author']) {

            $collector->addAssociation($config['class']['content'], 'mapManyToOne', array(
                'fieldName' => 'author',
                'targetEntity' => $config['class']['author'],
                'cascade' => array(
                    'persist',
                ),
                'mappedBy' => NULL,
                'inversedBy' => NULL,
                'joinColumns' => array(
                    array(
                        'name' => 'author_id',
                        'referencedColumnName' => 'id',
                        'onDelete' => 'SET NULL',
                    ),
                ),
                'orphanRemoval' => false,
            ));
        }

        if (class_exists($config['class']['contentfile']) && $config['class']['contentfile']) {

            $collector->addAssociation($config['class']['content'], 'mapOneToMany', array(
                'fieldName' => 'files',
                'targetEntity' => $config['class']['contentfile'],
                'cascade' => array(
                    'remove',
                    'persist',
                ),
                'mappedBy' => 'content',
                'orphanRemoval' => true,
                'orderBy' => array(
                    'pos' => 'ASC',
                ),

            ));


            $collector->addAssociation($config['class']['contentfile'], 'mapManyToOne', array(
                'fieldName' => 'content',
                'targetEntity' => $config['class']['content'],
                'cascade' => array(
                    'persist',
                ),
                'mappedBy' => NULL,
                'inversedBy' => NULL,
                'joinColumns' => array(
                    array(
                        'name' => 'content_id',
                        'referencedColumnName' => 'id',
                        'onDelete' => 'SET NULL',
                    ),
                ),
               // 'orphanRemoval' => false,
            ));

        }



        if (class_exists($config['class']['contentlink']) && $config['class']['contentlink']) {

            $collector->addAssociation($config['class']['content'], 'mapOneToMany', array(
                'fieldName' => 'links',
                'targetEntity' => $config['class']['contentlink'],
                'cascade' => array(
                    'all',
                ),
                'mappedBy' => 'content',
                'orphanRemoval' => true,
                'orderBy' => array(
                    'pos' => 'ASC',
                ),

            ));


            $collector->addAssociation($config['class']['contentlink'], 'mapManyToOne', array(
                'fieldName' => 'content',
                'targetEntity' => $config['class']['content'],
                'cascade' => array(
                    'persist',
                ),
                'mappedBy' => NULL,
                'inversedBy' => NULL,
                'joinColumns' => array(
                    array(
                        'name' => 'content_id',
                        'referencedColumnName' => 'id',
                        'onDelete' => 'SET NULL',
                    ),
                ),
                // 'orphanRemoval' => false,
            ));

        }
    }

}