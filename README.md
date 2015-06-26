[![Build Status](https://travis-ci.org/amonger/firewall.svg?branch=master)](https://travis-ci.org/amonger/firewall)
Firewall
========

This is a simple library which will have some action on a route being matched.

It is useful for legacy projects where some authorisation code my be copy
pasted into a header multiple times and there is no under-laying structure.

## Example ##

###Individual routes###
You can define routes individually by doing the following:
```php

use \amonger\Firewall\Firewall;

$firewall = new Firewall($_SERVER['REQUEST_URI']);

$firewall
    ->route('/managers\/.*/')
    ->unless(function ($uri) use ($container) {
       return $container['auth']->hasRole('manager');
    })
    ->handle(function () {
        throw new _401Exception();
    })
    ->execute();

```

###Multiple routes###
A scenario which is more likely is that you will have a single request
uri and multiple routes you'd like to handle. In this case you can use the builder
to setup the firewall.

```php
use \amonger\Firewall\Firewall;

$firewall = Firewall::getBuilder();
$firewall->setRequestUri($_SERVER['REQUEST_URI']);

$firewall
    ->route('/managers\/.*/')
    ->unless(function ($uri) use ($container) {
       return $container['auth']->hasRole('manager');
    })
    ->handle(function () {
        throw new _401Exception();
    });

$firewall
    ->route('/clients\/.*/')
    ->unless(function ($uri) use ($container) {
       return $container['auth']->hasRole('clients');
    })
    ->handle(function () {
        throw new _401Exception();
    });

Firewall::run($firewall);
```
