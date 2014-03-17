<?php

/**
 * {@inheritdoc}
 */

namespace Kairos\GoogleAnalyticsBundle\Services;

use Kairos\GoogleAnalytics\Tracker as BaseTracker;

class ClassicTracker extends BaseTracker
{
    /**
     * @param $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function setCookies($request)
    {
        $response = new \Symfony\Component\HttpFoundation\Response();

        foreach($request->getCookieParameters() as $key => $cookie) {
            $response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie($key, $cookie['value'], $cookie['expire']));
        }

        return $response;
    }
}