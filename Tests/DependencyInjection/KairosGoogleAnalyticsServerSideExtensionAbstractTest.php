<?php

namespace Kairos\GoogleAnalyticsServerSideBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;

use Kairos\GoogleAnalyticsServerSideBundle\DependencyInjection\KairosGoogleAnalyticsServerSideExtension;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
Abstract class KairosGoogleAnalyticsServerSideExtensionAbstractTest extends \PHPUnit_Framework_TestCase
{

    private $extension;
    private $container;

    protected function setUp()
    {
        $this->extension = new KairosGoogleAnalyticsServerSideExtension();

        $this->container = new ContainerBuilder();
        $this->container->registerExtension($this->extension);
    }

    /**
     * @param ContainerBuilder $container
     * @param string $resource
     * @return mixed
     */
    abstract protected function loadConfiguration(ContainerBuilder $container, $resource);


    public function testConfiguration()
    {
        $this->loadConfiguration($this->container, 'config');
        $this->container->compile();
        $this->assertEquals('UA-11111111-1',$this->container->getParameter('kairos_google_analytics_server_side.account_id'));
        $this->assertEquals('symfony.org',$this->container->getParameter('kairos_google_analytics_server_side.domain'));
        $this->assertTrue($this->container->getParameter('kairos_google_analytics_server_side.async'));
        $this->assertTrue($this->container->getParameter('kairos_google_analytics_server_side.ssl'));
        $this->assertTrue($this->container->getParameter('kairos_google_analytics_server_side.localhost'));
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testBadConfiguration()
    {
        // should throw an exception since some parameters are missing
        $this->loadConfiguration($this->container, 'bad_config');
        $this->container->compile();
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testWithoutConfiguration()
    {
        // should throw an exception since some parameters are missing
        $this->container->loadFromExtension($this->extension->getAlias());
        $this->container->compile();
    }
}
