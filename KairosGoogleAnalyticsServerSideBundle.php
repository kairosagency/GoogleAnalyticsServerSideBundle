<?php

namespace Kairos\GoogleAnalyticsServerSideBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Kairos\GoogleAnalyticsServerSideBundle\DependencyInjection\Compiler\LoggablePass;

class KairosGoogleAnalyticsServerSideBundle extends Bundle
{

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new LoggablePass());
    }
}
