<?php
/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Iphp\ContentBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $node = $treeBuilder->root('iphp_content')->children();

        $node->arrayNode('class')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('rubric')->defaultValue('Application\\Iphp\\CoreBundle\\Entity\\Rubric')->end()
                    ->scalarNode('content')->defaultValue('Application\\Iphp\\ContentBundle\\Entity\\Content')->end()
                    ->scalarNode('media')->defaultValue('Application\\Sonata\\MediaBundle\\Entity\\Media')->end()
                    ->scalarNode('author')->defaultValue('Application\\Iphp\\UserBundle\\Entity\\User')->end()
                    ->scalarNode('contentfile')->defaultValue('Application\Iphp\\ContentBundle\\Entity\\ContentFile')->end()
                ->end()
                ->end();


        /*$node->arrayNode ('field')
            ->addDefaultsIfNotSet()
            ->children()
            ->arrayNode('exclude');*/

        return $treeBuilder;
    }
}
