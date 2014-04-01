<?php

namespace Kairos\GoogleAnalyticsServerSideBundle\Tests\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class KairosGoogleAnalyticsServerSideExtensionTest extends KairosGoogleAnalyticsServerSideExtensionAbstractTest
{

    protected function loadConfiguration(ContainerBuilder $container, $resource)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Fixtures/'));
        $loader->load($resource.'.yml');
    }
}
