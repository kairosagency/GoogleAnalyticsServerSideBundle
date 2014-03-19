<?php

namespace Kairos\GoogleAnalyticsServerSideBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class KairosGoogleAnalyticsServerSideExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $container->setParameter('kairos_google_analytics_server_side.account_id', $config['account_id']);
        $container->setParameter('kairos_google_analytics_server_side.domain', $config['domain']);
        $container->setParameter('kairos_google_analytics_server_side.ssl', $config['ssl']);
        $container->setParameter('kairos_google_analytics_server_side.localhost', $config['localhost']);
    }
}
