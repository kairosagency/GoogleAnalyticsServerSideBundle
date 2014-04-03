<?php
namespace Kairos\GoogleAnalyticsServerSideBundle\Services;


use Kairos\GoogleAnalyticsServerSideBundle\Services\MeasurementProtocol;

class JsTracker
{
    /**
     * @var string
     */
    protected $trackingID;

    /**
     * @var string
     */
    protected $domain;

    /**
     * @var bool
     */
    protected $localhost;

    /**
     * @var \Kairos\GoogleAnalyticsServerSideBundle\Services\MeasurementProtocolTracker
     */
    protected $measurementProtocolTracker;

    /**
     * @param MeasurementProtocolTracker $measurementProtocolTracker
     * @param $trackingID
     * @param $domain
     */
    public function __construct(MeasurementProtocolTracker $measurementProtocolTracker, $trackingID, $domain, $localhost)
    {
        $this->measurementProtocolTracker = $measurementProtocolTracker;
        $this->trackingID   = $trackingID;
        $this->domain       = $domain;
        $this->localhost    = $localhost;
    }


    /**
     * Build gajs string to include in template
     *
     * @param null $tid
     * @param null $domain
     * @return string
     */
    public function getGAjs($tid = null, $domain = null, $clientId = null) {

        $args = array();

        if($tid) {
            $_tid = $tid;
        }
        else {
            $_tid = $this->trackingID;
        }

        if($domain) {
            $args['cookieDomain'] = $domain;
        }
        else {
            $args['cookieDomain'] = 'auto';
        }

        if($this->localhost) {
            $args['cookieDomain'] = 'none';
        }

        if($clientId) {
            $args['clientId'] = $clientId;
        }
        else {
            $args['clientId'] = $this->measurementProtocolTracker->getClientId();
        }


        $gascript = "<script>"
            . "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){"
            . "(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),"
            . "m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)"
            . "})(window,document,'script','//www.google-analytics.com/analytics.js','ga');";

        $gascript .= chr(13).chr(13)."ga('create', '" . $_tid . "', ". json_encode($args) ."); </script>";



        return $gascript;
    }
}