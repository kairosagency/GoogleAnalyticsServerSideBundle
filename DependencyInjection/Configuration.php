<?php

namespace GoogleAnalyticsServerSide\DependencyInjection;

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
        $rootNode = $treeBuilder->root('google_analytics');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode
            ->children()
                ->scalarNode('php_ga_accountID')
                    ->isRequired()->cannotBeEmpty()
                    ->info('your google account id')
                    ->example('UA-XXXXXXXX-XX')
                ->end()
                ->scalarNode('php_ga_domain')
                    ->defaultNull()
                    ->info('domain to track')
                    ->example('google.com')
                ->end()
                ->scalarNode('ssl')
                    ->defaultFalse()
                    ->info('use ssl')
                    ->example('true or false')
                ->end()
            ->end();



        return $treeBuilder;
    }
}
