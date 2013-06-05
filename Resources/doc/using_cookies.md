Using cookies
==================================

**In your bundle :**

You now can include the class in your controller

``` php
    use GoogleAnalytics;
```

And track page (or events etc.) :

``` php
    // Init config
    $config = new GoogleAnalytics\Lib\Config();

    // Force ip anonymize, mandatory to get ip geolocation
    $config->setAnonymizeIpAddresses(true);

    // Initilize GA Tracker and set the config
    $tracker = $this->get('googleanalytics');
    $tracker->setConfig($config);

    // Assemble Visitor information
    $visitor = new GoogleAnalytics\Lib\Visitor();
    // Get __utma info from cookie
    $visitor->fromUtma($this->getRequest()->cookies->get('__utma'));

    $visitor->fromServerVar($_SERVER);
    $visitor->setScreenResolution('1024x768');

    // Assemble Session information (could also get unserialized from PHP session)
    $session = new GoogleAnalytics\Lib\Session();
    // Get __utmb info from cookie
    $session->fromUtmb($this->getRequest()->cookies->get('__utmb'));


    // Assemble Page information
    $page = new GoogleAnalytics\Lib\Page('/mypage');
    $page->setTitle('My Page');

    // Track page view
    $gaRequest = $tracker->trackPageview($page, $session, $visitor);

    // Set the cookies
    $gaResponse = $tracker->setCookies($request);

    // Send the response
    $gaResponse->send();
```
