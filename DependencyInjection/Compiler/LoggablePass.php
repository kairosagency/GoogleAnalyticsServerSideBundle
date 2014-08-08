<?php

/**
 * This file is part of the BazingaGeocoderBundle package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace Kairos\GoogleAnalyticsServerSideBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Michal Dabrowski <dabrowski@brillante.pl>
 */
class LoggablePass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('kairos_google_analytics_server_side.measurement_protocol_tracker')) {
            return;
        }

        $definition = $container->getDefinition('kairos_google_analytics_server_side.measurement_protocol_tracker');
        $definition->setClass($container->getParameter('kairos_google_analytics_server_side.measurement_protocol_tracker.loggable_class'));
        $definition->addMethodCall('setLogger', array(new Reference('kairos_google_analytics_server_side.logger')));
    }
}
