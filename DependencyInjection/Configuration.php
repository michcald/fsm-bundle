<?php

namespace Michcald\FsmBundle\DependencyInjection;

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
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('michcald_fsm');

        $rootNode
            ->children()
                ->arrayNode('machines')
                    ->prototype('array')
                        ->children()
                            /*->scalarNode('class')
                                ->cannotBeEmpty()
                                ->isRequired()
                            ->end()
                             * 
                             */
                            ->arrayNode('states')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('type')
                                            ->cannotBeEmpty()
                                            ->defaultValue('normal')
                                            ->validate()
                                            ->ifNotInArray(array('initial', 'normal', 'final'))
                                                ->thenInvalid('Invalid state type "%s"')
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('transitions')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('from')
                                            ->cannotBeEmpty()
                                            ->isRequired()
                                        ->end()
                                        ->scalarNode('to')
                                            ->cannotBeEmpty()
                                            ->isRequired()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('callbacks')
                                ->children()
                                    ->arrayNode('before')
                                        ->prototype('array')
                                            ->children()
                                                ->scalarNode('on')->end()
                                                ->variableNode('do')->end()
                                                ->variableNode('from')->end()
                                                ->variableNode('to')->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                    ->arrayNode('after')
                                        ->prototype('array')
                                            ->children()
                                                ->scalarNode('on')->end()
                                                ->variableNode('do')->end()
                                                ->variableNode('from')->end()
                                                ->variableNode('to')->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
