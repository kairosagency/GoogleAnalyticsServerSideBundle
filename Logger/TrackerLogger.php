<?php

/**
 *
 * @license    MIT License
 */

namespace Kairos\GoogleAnalyticsServerSideBundle\Logger;

use Psr\Log\LoggerInterface;

/**
 * @author Michal Dabrowski <dabrowski@brillante.pl>
 */
class TrackerLogger
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var array
     */
    protected $requests = array();

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @param string                      $value         hit to send
     * @param float                       $duration      gapi call duration
     * @param \SplObjectStorage  $results
     */
    public function logRequest($value, $duration, $results)
    {
        if (null !== $this->logger) {
            $this->logger->info(sprintf("%s %0.2f ms", $value, $duration));
        }

        if ($results instanceof \SplObjectStorage) {
            foreach ($results as $result) {
                $data[] = $result->toArray();
            }
        } else {
            $data = $results;
        }

        $this->requests[] = array(
            'value'         => $value,
            'duration'      => $duration,
            'result'        => $data,
        );
    }

    /**
     * Returns an array of the logged requests.
     *
     * @return array
     */
    public function getRequests()
    {
        return $this->requests;
    }
}
