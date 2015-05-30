<?php

require_once "vendor/autoload.php";

use amonger\Firewall\_401Exception;
use amonger\Firewall\Firewall;

$firewall = new Firewall($_SERVER['REQUEST_URI']);
$firewall
    ->route('/managers\/.*/')
    ->unless(function () use ($container) {
       return $container['auth']->hasRole('manager');
    })
    ->handle(function () {
        throw new _401Exception();
    });
