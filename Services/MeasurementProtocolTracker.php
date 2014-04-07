<?php
namespace Kairos\GoogleAnalyticsServerSideBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Kairos\GoogleAnalyticsServerSideBundle\Listener\CookieSetterListener;
use Krizon\Google\Analytics\MeasurementProtocol\MeasurementProtocolClient;

class MeasurementProtocolTracker
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var string
     */
    protected $trackingID;

    /**
     * @var string
     */
    protected $domain;

    /**
     * @var string
     */
    protected $client;

    /**
     * Google analytics client id
     *
     * @var string
     */
    protected $clientId;

    /**
     * @var bool
     */
    protected $updateCookie;

    /**
     * @var bool
     */
    protected $hasCookie;

    /**
     * @var bool
     */
    protected $async;

    /**
     * @param ContainerInterface $container
     * @param string $trackingID
     * @param string $domain
     * @param bool $ssl
     */
    public function __construct(ContainerInterface $container, $trackingID, $domain, $ssl = false, $async = false, $timeout = 10, $connect_timeout = 2)
    {
        $this->async        = $async;
        $this->trackingID   = $trackingID;
        $this->domain       = $domain;

        $this->container    = $container;
        $this->client       = MeasurementProtocolClient::factory(
            array(
                'ssl' => $ssl,
                'request.options' => array(
                    'timeout'         => $timeout,
                    'connect_timeout' => $connect_timeout
                )
            )
        );

        if ($this->container->isScopeActive('request')) {
            $this->request      = $this->container->get('request');
            $this->clientId     = $this->setClientId();
        }
    }

    /**
     * @return string
     */
    private function setClientId()
    {
        $this->updateCookie = false;
        $this->hasCookie    = true;

        $gamp = $this->request->cookies->get('__gamp');
        $ga = $this->request->cookies->get('_ga');

        // case gajs is already or concurrently running
        if($ga) {
            // we parse client id (we should be carefull with this part, in case client id changes)
            $gaClientId = self::parseGa($ga);

            // we force our cookie to sync with ga cookie client Id
            if($gamp != $gaClientId) {
                $this->updateCookie = true;
            }
            return $gaClientId;
        }

        //if gajs is not running and there is no client id we generate a new one
        if(is_null($gamp)) {
            $this->updateCookie = true;
            $this->hasCookie = false;
            return self::gaid();
        }

        return $gamp;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return bool
     */
    public function hasCookie()
    {
        return $this->hasCookie;
    }

    /**
     * @return bool
     */
    public function shouldUpdateCookie()
    {
        return $this->updateCookie;
    }


    public function track($hitType, $args)
    {
        if($this->updateCookie) {
            $this->setGampCookie($this->clientId);
        }

        $default = array(
            'tid' => $this->trackingID,
            'cid' => $this->clientId,
            'ua' => $this->request->server->get('HTTP_USER_AGENT'),
            'uip' => $this->request->getClientIp()
        );

        $locales = $this->request->getLanguages();
        if(count($locales) > 0){
            $default['ul'] = $locales[0];
        }

        //check if the function is callable
        if(is_callable(array($this->client, $hitType), true)) {
            if($this->async) {
                register_shutdown_function(array($this, 'trackAndCatch'), array_merge($default, $args));
                return 'Asynchronous google measurement protocol http request';
            }
            else {
                return $this->trackAndCatch( $hitType, array_merge($default, $args));
            }
        }
        else
            return null;
    }

    /**
     * we catch all errors, errors should not make the whole app fails
     * (eg : timeout, 403 or 404 ...)
     *
     * @param $hitType
     * @param $args
     * @return mixed|string
     */
    private function trackAndCatch($hitType, $args)
    {
        try {
            return call_user_func(array($this->client, $hitType), $args);
        } catch(\Exception $e) {
            return '[Guzzle error] ' . $e->getMessage();
        }
    }


    /**
     * @param string $cookieValue
     */
    public function setGampCookie($cookieValue)
    {
        $now = new \DateTime();
        $in6months = $now->add(new \DateInterval('P6M'));
        $cookieSetterListener = new CookieSetterListener(
            array('__gamp' =>  array(
                'value' => $cookieValue,
                'expire' => $in6months->getTimestamp())
            )
        );
        // we set the cookie value in the kernel.response event
        $this->container->get('event_dispatcher')->addListener('kernel.response', array($cookieSetterListener, 'onKernelResponse'));
    }


    /**
     * Parse ga cookie client id
     *
     * @param $ga
     * @return string
     */
    private static function parseGa($ga)
    {
        return implode('.', array_slice(explode('.', $ga), -2, 2));
    }


    public static function gaid()
    {
        return sprintf( '%04x%04x%04x.%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }
}