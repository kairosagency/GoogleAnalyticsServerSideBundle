<?php
namespace Kairos\GoogleAnalyticsServerSideBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

use Kairos\GoogleAnalyticsServerSideBundle\Listener\CookieListener;

use Krizon\Google\Analytics\MeasurementProtocol\MeasurementProtocolClient;

class MeasurementProtocol
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
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
     * @param ContainerInterface $container
     * @param string $trackingID
     * @param string $domain
     * @param bool $ssl
     */
    public function __construct(ContainerInterface $container, $trackingID, $domain, $ssl = false)
    {
        $this->container    = $container;
        $this->request      = $this->container->get('request');
        $this->trackingID   = $trackingID;
        $this->domain       = $domain;

        $this->client = MeasurementProtocolClient::factory(array('ssl' => $ssl));
    }


    public function track($hitType, $args) {


        //get __gatm cookie content
        $gamp = $this->request->cookies->get('__gatmp');

        // if cookie is null, we create a new cookie
        if(is_null($gamp)) {
            $gamp = self::uuid4();

            $now = new \DateTime();
            $in2years = $now->add(new \DateInterval('P2Y'));

            $cookieListener = new CookieListener(
                array('__gamp' =>  array(
                    'value' => $gamp,
                    'expire' => $in2years->getTimestamp())
                )
            );
            // we set the cookie value in the kernel.response event
            $this->container->get('event_dispatcher')->addListener('kernel.response', array($cookieListener, 'onKernelResponse'));
        }

        $default = array(
            'cid' => $gamp,
            'ua' => $this->request->server->get('HTTP_USER_AGENT'),
            'uip' => $this->request->getClientIp()
        );


        // est ce que je dois vraiment vérifier ça ?
        if(is_callable(array($this->client, $hitType), true))
            return call_user_func(array($this->client, $hitType), array_merge($default, $args));
        else
            return null;
    }


    public static function uuid4() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }

}