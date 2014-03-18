<?php
namespace Kairos\GoogleAnalyticsServerSideBundle\Services;


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
     * @param ContainerInterface $container
     * @param string $trackingID
     * @param string $domain
     * @param bool $ssl
     */
    public function __construct($trackingID, $domain)
    {
        $this->trackingID   = $trackingID;
        $this->domain       = $domain;
    }


    /**
     * Build gajs string to include in template
     *
     * @return string
     */
    public function getGAjs() {
        $gascript = "<script>"
            . "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){"
            . "(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),"
            . "m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)"
            . "})(window,document,'script','//www.google-analytics.com/analytics.js','ga');"
            . "ga('create', '". $this->trackingID ."', '". $this->domain ."');"
            ."</script>";

        return $gascript;
    }
}