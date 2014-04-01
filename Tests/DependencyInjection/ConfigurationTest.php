<?php

namespace Kairos\GoogleAnalyticsServerSideBundle\Tests\DependencyInjection;

use Kairos\GoogleAnalyticsServerSideBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testGetConfigTreeBuilder()
    {
        $config = Yaml::parse(file_get_contents(__DIR__.'/../Fixtures/config.yml'));

        $configuration = new Configuration();
        $treeBuilder = $configuration->getConfigTreeBuilder();
        $processor = new Processor;

        $config = $processor->process($treeBuilder->buildTree(), $config);
    }
}
