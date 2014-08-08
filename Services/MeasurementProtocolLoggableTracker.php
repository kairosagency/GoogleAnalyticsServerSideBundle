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
        /*ob_start();
        var_dump($args);
        $dump = ob_end_clean();*/


        $startTime = microtime(true);
        $results   = parent::track($hitType, $args);
        $duration  = (microtime(true) - $startTime) * 1000;

        $this->logger->logRequest(
            sprintf("[google analytics] sending hit %s to account %s with cid %s and params: %s", $hitType, $this->trackingID, $this->clientId, json_encode($args)),
            $duration,
            $results
        );

        return $results;
    }
}