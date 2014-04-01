Getting Started With Server-Side Google Analytics PHP Client
==================================

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kairosagency/GoogleAnalyticsServerSideBundle/badges/quality-score.png?s=ee8ccb653cbc357043870a02330d0d6367f3465c)](https://scrutinizer-ci.com/g/kairosagency/GoogleAnalyticsServerSideBundle/)
[![Build Status](https://travis-ci.org/kairosagency/GoogleAnalyticsServerSideBundle.svg?branch=develop)](https://travis-ci.org/kairosagency/GoogleAnalyticsServerSideBundle)

## Important

This bundle has been updated to support universal analytics.


## Summary :

Google Analytics Server Side Bundle is aimed at sending google analytics hits from a server.
This can be very usefull for an app, or in case google analytics is blocked.

This bundle made originally use of the great project from UnitedPrototype : http://code.google.com/p/php-ga/


## Requirements

Requires PHP 5.3 as namespaces and closures are used. Has no other dependencies and can be used independantly from any framework or whatsoever environment.

## Installation :

**In your composer file file :**

``` js
    {
        "require": {
            "kairos/googleanalyticsserversidebundle": "dev-master"
        }
    }
```

**Update your composer :**

``` bash
    php composer.phar update kairos/googleanalyticsserversidebundle
```

Composer will install the bundle to your project's vendor/kairos directory.
    
**Enable the bundle in the AppKernel file :**

``` php
    <?php
    // app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Kairos\GoogleAnalyticsServerSideBundle\KairosGoogleAnalyticsServerSideBundle(),
        );
    }
```

**In your config.yml**

``` yaml
    kairos_google_analytics_server_side:
        account_id:  UA-XXXXXXXX-XX
        domain:     your.domain
        ssl: false
```

**If you want to use also js tracking**

``` yaml
    twig:
        globals:
            gajs: "@ga_js_tracker"
```

## How To use :

### With the new universal analytics :

**Super Bonus !**
You can use both serverside and client side ! The session will be synched between your serverside and client side code so no doubles

#### Serverside usage :

You can use directly the documentation given by google at the address :
https://developers.google.com/analytics/devguides/collection/protocol/v1/parameters

``` php

    // Call tracker from container
    $this->container->get('ga_mp_tracker')->track('pageview',
        array(
            'dp' => 'your.address.com',
            'dt' => 'your title'
        )
    );

    // Track event
    $this->container->get('ga_mp_tracker')->track('event',
        array(
            'dp' => 'your.address.com',
            'dt' => 'your title',
            'ec' => 'event category',
            'ea' => 'event action',
            'el' => 'event label',
        )
    );
```

#### Client side usage :

To use ga.js on client side, you have to register the ga_js_tracker service in your twig config, then you'll be able to call it this way :

```
    {{ gajs.getGAjs()|raw }}
```

You can override trackingId and Domain by calling the function with these parameters :

```
    {{ gajs.getGAjs('trackingID','domain')|raw }}
```

Don't forget the raw if you don't want the tag to be escaped. Your ga.js session will be automatically synched with your server side session (client id is shared in the cookies).



#### With an old google analytics account :

``` php

    // Initilize GA Tracker
    $tracker = $this->get('google_analytics');
    
    // Assemble Visitor information (could also get unserialized from database)
    $visitor = new GoogleAnalytics\Visitor();
    $visitor->setIpAddress($_SERVER['REMOTE_ADDR']);
    $visitor->setUserAgent($_SERVER['HTTP_USER_AGENT']);
    $visitor->setScreenResolution('1024x768');
    
    // Assemble Session information (could also get unserialized from PHP session)
    $session = new GoogleAnalytics\Session();
    
    // Assemble Page information
    $page = new GoogleAnalytics\Page('/page.html');
    $page->setTitle('My Page');
    
    // Track page view
    $tracker->trackPageview($page, $session, $visitor);
```

- [Read more](https://github.com/kairosagency/GoogleAnalyticsBundle/tree/master/Resources/doc/index.md)
- [Using Cookies](https://github.com/kairosagency/GoogleAnalyticsBundle/tree/master/Resources/doc/using_cookies.md)




## Thanks

This package is directly based on this project from UnitedPrototype : http://code.google.com/p/php-ga/
