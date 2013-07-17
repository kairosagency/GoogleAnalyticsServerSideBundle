<?php

/**
 * {@inheritdoc}
 */

namespace GoogleAnalyticsServerSide\Lib;

use Kairos\GoogleAnalytics\Tracker as BaseTracker;

class Tracker extends BaseTracker
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