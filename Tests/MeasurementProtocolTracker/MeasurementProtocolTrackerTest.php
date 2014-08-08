<?php
/**
 * Created by PhpStorm.
 * User: alexandre
 * Date: 31/03/2014
 * Time: 18:13
 */

namespace Kairos\GoogleAnalyticsServerSideBundle\Tests\MeasurementProtocolTrackerTests;



use Kairos\GoogleAnalyticsServerSideBundle\Services\MeasurementProtocolTracker;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Kairos\GoogleAnalyticsServerSideBundle\DependencyInjection\KairosGoogleAnalyticsServerSideExtension;
use Symfony\Component\DependencyInjection\Scope;
use Symfony\Component\HttpFoundation\Request;


class MeasurementProtocolTrackerTest extends \PHPUnit_Framework_TestCase
{

    private $extension;

    private $container;

    private $server;

    private $mptracker;


    public function buildContainerWithRequest(Request $request)
    {
        $this->container = new ContainerBuilder();
        $this->container->registerExtension($this->extension);

        // makes isScopeActive true
        $this->container->addScope(new Scope('request'));
        $this->container->enterScope('request');

        $loader = new YamlFileLoader($this->container, new FileLocator(__DIR__.'/../Fixtures/'));
        $loader->load('config.yml');

        $this->container->set('request', $request);
        $this->container->compile();
        $this->mptracker = $this->container->get('ga_mp_tracker');
    }

    /**
     * Init container
     */
    protected function setUp()
    {
        $this->server = $server = array(
            'USER' =>  'www-data',
            'HOME' =>  '/var/www' ,
            'FCGI_ROLE' =>  'RESPONDER' ,
            'QUERY_STRING' =>  '' ,
            'REQUEST_METHOD' =>  'GET' ,
            'CONTENT_TYPE' =>  '' ,
            'CONTENT_LENGTH' =>  '' ,
            'SERVER_PROTOCOL' =>  'HTTP/1.1' ,
            'GATEWAY_INTERFACE' =>  'CGI/1.1' ,
            'SERVER_SOFTWARE' =>  'nginx/1.1.19' ,
            'REMOTE_ADDR' =>  '10.0.2.2',
            'REMOTE_PORT' =>  '60820',
            'SERVER_ADDR' =>  '10.0.2.15' ,
            'SERVER_PORT' =>  '8080' ,
            'SERVER_NAME' =>  'localhost',
            'HTTPS' =>  'off' ,
            'REDIRECT_STATUS' =>  '200' ,
            'HTTP_HOST' =>  'localhost:8080' ,
            'HTTP_CONNECTION' =>  'keep-alive' ,
            'HTTP_CACHE_CONTROL' =>  'max-age=0' ,
            'HTTP_ACCEPT' =>  'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8' ,
            'HTTP_USER_AGENT' =>  'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.152 Safari/537.36',
            'HTTP_ACCEPT_ENCODING' =>  'gzip,deflate,sdch' ,
            'HTTP_ACCEPT_LANGUAGE' =>  'fr-FR,fr;q=0.8,en;q=0.6,en-US;q=0.4' ,
            'HTTP_COOKIE' =>  '',
        );

        $this->extension = new KairosGoogleAnalyticsServerSideExtension();

        $this->buildContainerWithRequest($this->getCustomRequest());

    }


    public function getCustomRequest($cookies = array(), $server = array())
    {
        //var_dump($cookies);
        $server = array_merge($this->server, $server);
        return  Request::create('dev.sf2.org', 'GET', array(), $cookies, array(), $server);
    }

/*
    public function testMeasurementProtocolTracker()
    {
        var_dump($this->container->get('request'));
    }*/


    public function testGetClientIdWithoutCookies()
    {
        $this->assertNotNull($this->mptracker->getClientId());
    }


    public function getCookiesProvider() {
        return array(
            array(
                array('__gamp' => 'f48e37b10a8c.1d809940858a', '_ga' => 'GA1.1.f48e37b10a8c.1d809940858a'),
                'f48e37b10a8c.1d809940858a',
                false,
            ),
            array(
                array('__gamp' => '111111111111.1d809940858a', '_ga' => 'GA1.1.f48e37b10a8c.1d809940858a'),
                'f48e37b10a8c.1d809940858a',
                true,
            ),
            array(
                array('__gamp' => 'f48e37b10a8c.1d809940858a'),
                'f48e37b10a8c.1d809940858a',
                false,
            ),
            array(
                array('_ga' => 'GA1.1.f48e37b10a8c.1d809940858a'),
                'f48e37b10a8c.1d809940858a',
                true,
            )
        );
    }

    /**
     * test getClientId
     * @dataProvider getCookiesProvider
     */
    public function testGetClientIdWithCookies($cookies, $cid, $shouldUpdateCookie)
    {
        $this->buildContainerWithRequest($this->getCustomRequest($cookies));
        $this->assertNotNull($this->mptracker->getClientId());
        $this->assertEquals($cid, $this->mptracker->getClientId());
    }


    /**
     * test getClientId
     * @dataProvider getCookiesProvider
     */
    public function testUpdateCookie($cookies, $cid, $shouldUpdateCookie)
    {
        $this->buildContainerWithRequest($this->getCustomRequest($cookies));
        $this->assertEquals($shouldUpdateCookie, $this->mptracker->shouldUpdateCookie());
    }


    /**
     * test hascookie
     * @dataProvider getCookiesProvider
     */
    public function testHasCookie($cookies, $cid)
    {
        $this->buildContainerWithRequest($this->getCustomRequest($cookies));
        $this->assertTrue($this->mptracker->HasCookie());
    }

    public function getGaProvider() {
        return array(
            array(
                array('_ga' => 'hhjkhj.lkjlkjlklk.f48e37b10a8c.1d809940858a'),
                'f48e37b10a8c.1d809940858a'
            ),
            array(
                array('_ga' => 'GA134.143.f48e37b10a8c.1d809940858a'),
                'f48e37b10a8c.1d809940858a'
            ),
            array(
                array('_ga' => 'GA1.1.f48e37b10a8c.1d809940858a'),
                'f48e37b10a8c.1d809940858a'
            )
        );
    }

    /**
     * test parseGa
     * @dataProvider getGaProvider
     */
    public function testParseGa($cookie, $cid)
    {
        $this->buildContainerWithRequest($this->getCustomRequest($cookie));
        $this->assertEquals($cid, $this->mptracker->getClientId());
    }


    /**
     * test gaid
     */
    public function testGaid()
    {
        $gaid = MeasurementProtocolTracker::gaid();
        $this->assertEquals(1,preg_match("/^[a-z0-9]{12}\.[a-z0-9]{12}$/", $gaid));
    }

} 