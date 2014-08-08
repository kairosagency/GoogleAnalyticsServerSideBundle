<?php

/**
 *
 * @license    MIT License
 */

namespace Kairos\GoogleAnalyticsServerSideBundle\Logger;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

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

        if($results instanceof \Guzzle\Http\Message\Response) {
            $data = $results->getRawHeaders();
        }
        else {
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

    /**
     * @param string $message
     * @param array $context
     * @return null
     */
    public function error($message, array $context = array()) {
        $this->logger->error($message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     * @return null
     */
    public function info($message, array $context = array()) {
        $this->logger->info($message, $context);
    }
}
