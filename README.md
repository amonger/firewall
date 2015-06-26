[![Build Status](https://travis-ci.org/amonger/firewall.svg?branch=master)](https://travis-ci.org/amonger/firewall)
Firewall
========

This is a simple library which will have some action on a route being matched.

It is useful for legacy projects where some authorisation code my be copy
pasted into a header multiple times and there is no under-laying structure.

## Example ##

```php

$firewall = new \amonger\Firewall\Firewall($_SERVER['REQUEST_URI']);

$firewall
    ->route('/managers\/.*/')
    ->unless(function ($uri) use ($container) {
       return $container['auth']->hasRole('manager');
    })
    ->handle(function () {
        throw new _401Exception();
    });

```
