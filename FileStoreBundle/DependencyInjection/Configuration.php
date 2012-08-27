<?php

namespace Iphp\FileStoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration.
 *
 * @author Vitiko <vitiko@mail.ru>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Gets the configuration tree builder for the extension.
     *
     * @return Tree The configuration tree builder
     */
    public function getConfigTreeBuilder()
    {
        $tb = new TreeBuilder();
        $root = $tb->root('iphp_file_store');

        $root
            ->children()
                ->scalarNode('db_driver')->isRequired()->end()
                //->scalarNode('web_dir_name')->defaultValue('web')->end()
               // ->scalarNode('twig')->defaultTrue()->end()

                ->arrayNode('mappings')
                    ->useAttributeAsKey('id')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('upload_dir')->end()

                         /* can differ from iphp.web_dir if use capifony shared folders */
                            ->scalarNode ('base_web_dir') ->end()
                            ->arrayNode('namer')
                               ->treatFalseLike(array ())
                               ->defaultValue(array ('translit' => array('service' => 'iphp.filestore.namer.default')))

                               ->useAttributeAsKey('id')
                               ->prototype('array')
                               ->addDefaultsIfNotSet()
                                  ->children()

                                     ->scalarNode('service')->defaultValue('iphp.filestore.namer.default')->end()
                                     ->arrayNode('params')
                                        ->useAttributeAsKey('name')
                                        ->prototype('scalar')->end()
                                     ->end()
                           //    ->treatFalseLike(array ('service' => null, 'method' => null))
                            //   ->addDefaultsIfNotSet()

                             /*  ->children()



                               ->end()*/
                                 ->end()
                               ->end()
                            ->end()

                            ->arrayNode('directory_namer')
                                ->treatFalseLike(array ('service' => null, 'method' => null))
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('service')->defaultValue('iphp.filestore.directory_namer.default')->end()
                                    ->scalarNode('method')->defaultValue("")->end()
                                    ->arrayNode('params')
                                         ->useAttributeAsKey('name')
                                         ->prototype('scalar')->end()
                                    ->end()
                                ->end()

                            ->end()

                            ->scalarNode('delete_on_remove')->defaultTrue()->end()
                            ->scalarNode('overwrite_duplicates')->defaultFalse()->end()

                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $tb;
    }
}
