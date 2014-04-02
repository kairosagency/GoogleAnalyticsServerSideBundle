<?php
namespace Kairos\GoogleAnalyticsServerSideBundle\Services;

use Kairos\GoogleAnalyticsServerSideBundle\Logger\TrackerLogger;

class MeasurementProtocolLoggableTracker extends MeasurementProtocolTracker
{

    /**
     * @var  \Kairos\GoogleAnalyticsServerSideBundle\Logger\TrackerLogger
     */
    protected $logger;


    /**
     *
     * @param MeasurementProtocolTracker $logger
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
            sprintf("[google analytics] sending hit %s", $hitType),
            $duration,
            $results
        );

        return $results;
    }
}