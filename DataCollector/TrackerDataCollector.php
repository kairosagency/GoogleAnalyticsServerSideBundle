<?php

/**
 * @license    MIT License
 */

namespace Kairos\GoogleAnalyticsServerSideBundle\DataCollector;

use Kairos\GoogleAnalyticsServerSideBundle\Logger\TrackerLogger;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class TrackerDataCollector extends DataCollector
{
    /**
     * @var TrackerLogger
     */
    protected $logger;

    /**
     *
     * @param TrackerLogger $logger
     */
    public function __construct(TrackerLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = array(
            'requests' => null !== $this->logger ? $this->logger->getRequests() : array(),
        );
    }

    /**
     * Returns an array of collected requests.
     *
     * @return array
     */
    public function getRequests()
    {
        return $this->data['requests'];
    }

    /**
     * Returns the number of collected requests.
     *
     * @return integer
     */
    public function getRequestsCount()
    {
        return count($this->data['requests']);
    }

    /**
     * Returns the execution time of all collected requests in seconds.
     *
     * @return float
     */
    public function getTime()
    {
        $time = 0;
        foreach ($this->data['requests'] as $command) {
            $time += $command['duration'];
        }

        return $time;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'gamptracker';
    }
}
