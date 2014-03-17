<?php
namespace Kairos\GoogleAnalyticsServerSideBundle\Listener;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class CookieListener
{
    protected $cookies;

    public function __construct($cookies)
    {
        $this->cookies = $cookies;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();
        // update client cookies with google analytics cookies
        foreach($this->cookies as $key => $cookie) {
            $response->headers->setCookie(new Cookie($key, $cookie['value'], $cookie['expire']));
        }
    }
}