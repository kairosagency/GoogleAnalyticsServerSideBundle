Getting Started With Server-Side Google Analytics PHP Client
==================================

## Important  

*This version is deprecated, we have tagged it version 1.0.0 so use this in your composer.json if you are still using this version*  

You should rather use version 2.0.0 which is the new starting point of the library.

This package is directly based on this project from UnitedPrototype : http://code.google.com/p/php-ga/ 

This package is aimed at using php-ga in symfony 2 as a vendor and a service


## Summary :
"ga.js in PHP" - Implementation of a generic server-side Google Analytics client in PHP that implements nearly every parameter and tracking feature of the original GA Javascript client.

We love Google Analytics and want to contribute to its community with this PHP client implementation. It is intended to be used stand-alone or in addition to an existing Javascript library implementation.

It's PHP, but porting it to e.g. Ruby or Python should be easy. Building this library involved weeks of documentation reading, googling and testing - therefore its source code is thorougly well-documented.

The PHP client has nothing todo with the Data Export or Management APIs, although you can of course use them in combination.

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
            new GoogleAnalyticsServerSide\GoogleAnalyticsBundle(),
        );
    }
```

**In your parameters.yml**

``` yaml
    parameters:
        php_ga_accountID:   UA-12345678-9
        php_ga_domain:      yourwebsite.com
```

## How To use :

**In your bundle :**

You now can include the class in your controller

``` php
    use Kairos\GoogleAnalytics;
```

And track page (or events etc.) :

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
