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
     * @var \Kairos\GoogleAnalyticsServerSideBundle\Services\MeasurementProtocolTracker
     */
    protected $measurementProtocolTracker;

    /**
     * @param MeasurementProtocolTracker $measurementProtocolTracker
     * @param $trackingID
     * @param $domain
     */
    public function __construct(MeasurementProtocolTracker $measurementProtocolTracker, $trackingID, $domain)
    {
        $this->measurementProtocolTracker = $measurementProtocolTracker;
        $this->trackingID   = $trackingID;
        $this->domain       = $domain;
    }


    /**
     * Build gajs string to include in template
     *
     * @param null $tid
     * @param null $domain
     * @return string
     */
    public function getGAjs($tid = null, $domain = null) {

        if($tid) {
            $_tid = $tid;
        }
        else {
            $_tid = $this->trackingID;
        }

        if($domain) {
            $_domain = $domain;
        }
        else {
            $_domain = $this->trackingID;
        }

        $gascript = "<script>"
            . "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){"
            . "(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),"
            . "m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)"
            . "})(window,document,'script','//www.google-analytics.com/analytics.js','ga');";

        // we force gajs to use our client id
        if($this->measurementProtocolTracker->hasCookie()) {
            $gascript .= "ga('create', '". $_tid ."', '". $_domain ."', { 'clientId' : '". $this->measurementProtocolTracker->getClientId() ."' }); </script>";
        }
        else {
            $gascript .= "ga('create', '". $_tid ."', '". $_domain ."'); </script>";
        }



        return $gascript;
    }
}