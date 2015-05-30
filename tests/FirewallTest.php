<?php

use amonger\Firewall\Firewall;

class FirewallTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Firewall $firewall
     */
    protected $firewall;

    public function setUp()
    {
        $this->firewall = new Firewall('/managers/index.php');
    }

    public function testUriIsMatched()
    {
        $result = $this
            ->firewall
            ->route('/\/managers\/index.php/')
            ->handle(function () {});
        $this->assertTrue($result);
    }


    public function testUriIsMatchedOutOfMany()
    {
        $result = $this
            ->firewall
            ->route('/\/staff\/index.php/')
            ->route('/\/managers\/index.php/')
            ->handle(function () {});
        $this->assertTrue($result);
    }

    public function testUriIsMatchedAndIsAuthorised()
    {
        $result = $this
            ->firewall
            ->route('/\/managers\/index.php/')
            ->unless(function () {
                return true;
            })
            ->handle(function () {});
        $this->assertFalse($result);
    }

    public function testMultipleAuthorisationConditions()
    {
        $result = $this
            ->firewall
            ->route('/\/managers\/index.php/')
            ->unless(function () {
                return true;
            })
            ->unless(function () {
                return false;
            })
            ->unless(function () {
                return false;
            })
            ->handle(function () {});
        $this->assertFalse($result);
    }

    public function testUriIsNotMatchedAndIsNotAuthorised()
    {
        $result = $this
            ->firewall
            ->route('/\/staff\/index.php/')
            ->unless(function () {
                return false;
            })
            ->handle(function () {});
        $this->assertFalse($result);
    }

    public function testUriIsNotMatched()
    {
         $result = $this
            ->firewall
            ->route('/\/staff\/index.php/')
            ->handle(function () {});
        $this->assertFalse($result);
    }
}
