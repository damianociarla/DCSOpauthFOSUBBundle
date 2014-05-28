<?php

namespace DCS\OpauthFOSUBBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('dcs_opauth_fosub');

        $rootNode
            ->children()
                ->scalarNode('db_driver')
                    ->defaultValue('orm')
                    ->validate()
                    ->ifNotInArray(array('orm'))
                        ->thenInvalid('Value "%s" is not a valid db_driver')
                    ->end()
                ->end()
                ->scalarNode('oauth_model')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('oauth_manager')
                    ->cannotBeEmpty()
                    ->defaultValue('dcs_opauth_fosub.manager.oauth.default')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
