<?php

require_once "vendor/autoload.php";

$firewall = new \Firewall\Firewall($_SERVER['REQUEST_URI']);
$firewall
    ->route('/managers\/.*/')
    ->unless(function () use ($container) {
       return $container['auth']->hasRole('manager');
    })
    ->handle(function () {
        throw new _401Exception();
    });
