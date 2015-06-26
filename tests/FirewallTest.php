<?php

use amonger\Firewall\Firewall;

class FirewallTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Firewall $firewall
     */
    protected $firewall;

    protected $uri;

    public function setUp()
    {
        $this->uri = '/managers/index.php';
        $this->firewall = new Firewall($this->uri);
    }

    public function testUriIsMatched()
    {
        $result = $this
            ->firewall
            ->route('/\/managers\/index.php/')
            ->handle(function () {
            })
            ->execute();
        $this->assertTrue($result);
    }


    public function testUriIsMatchedOutOfMany()
    {
        $result = $this
            ->firewall
            ->route('/\/staff\/index.php/')
            ->route('/\/managers\/index.php/')
            ->handle(function () {
            })
            ->execute();
        $this->assertTrue($result);
    }

    public function testUriIsMatchedAndIsAuthorised()
    {
        $result = $this
            ->firewall
            ->route('/\/managers\/index.php/')
            ->unless(function ($uri) {
                return true;
            })
            ->handle(function () {
            })
            ->execute();
        $this->assertFalse($result);
    }

    public function testMultipleAuthorisationConditions()
    {
        $result = $this
            ->firewall
            ->route('/\/managers\/index.php/')
            ->unless(function ($uri) {
                return true;
            })
            ->unless(function ($uri) {
                return false;
            })
            ->unless(function ($uri) {
                return false;
            })
            ->handle(function ($uri) {
            })
            ->execute();
        $this->assertFalse($result);
    }

    public function testUriIsNotMatchedAndIsNotAuthorised()
    {
        $result = $this
            ->firewall
            ->route('/\/staff\/index.php/')
            ->unless(function ($uri) {
                return false;
            })
            ->handle(function () {
            })
            ->execute();
        $this->assertFalse($result);
    }

    public function testUriIsNotMatched()
    {
        $result = $this
            ->firewall
            ->route('/\/staff\/index.php/')
            ->handle(function () {
            })
            ->execute();
        $this->assertFalse($result);
    }

    public function testUriIsReturnedIntoUnless()
    {
        $uriIsSame = false;
        $definedUri = $this->uri;

        $result = $this
            ->firewall
            ->route('/\/managers\/index.php/')
            ->unless(function ($uri) use (&$uriIsSame, $definedUri) {
                $uriIsSame = $definedUri === $uri;
            })
            ->handle(function(){})
            ->execute();

        $this->assertTrue($uriIsSame);
    }
}
