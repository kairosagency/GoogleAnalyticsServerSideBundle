<?php
namespace Kairos\GoogleAnalyticsServerSideBundle\Services;

use Kairos\GoogleAnalyticsServerSideBundle\Logger\TrackerLogger;

class MeasurementProtocolLoggableTracker extends MeasurementProtocolTracker
{

    /**
     * @var  TrackerLogger
     */
    protected $logger;


    /**
     *
     * @param TrackerLogger $logger
     */
    public function setLogger(TrackerLogger $logger = null)
    {
        $this->logger = $logger;
    }


    /**
     * {@inheritdoc}
     */
    public function track($hitType, $args)
    {
        if (null === $this->logger) {
            return parent::track($hitType, $args);
        }

        $startTime = microtime(true);
        $results   = parent::track($hitType, $args);
        $duration  = (microtime(true) - $startTime) * 1000;

        $this->logger->logRequest(
            sprintf("[google analytics] sending hit %s to account %s on domain %s", array($hitType, $args['tid'])),
            $duration,
            $results
        );

        return $results;
    }
}