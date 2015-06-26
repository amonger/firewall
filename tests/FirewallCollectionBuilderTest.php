<?php


use amonger\Firewall\Collection\Collection;
use amonger\Firewall\Collection\CollectionBuilder;

class FirewallCollectionBuilderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Firewall $firewall
     */
    protected $firewallBuilder;

    public function setUp()
    {
        $this->uri = '/managers/index.php';
        $this->firewallBuilder = new CollectionBuilder(new Collection, $this->uri);
    }

    public function testBuildCollection()
    {
        $builder = $this->firewallBuilder;

        $builder
            ->route('/\/managers\/index.php/')
            ->unless(function ($uri) {})
            ->handle(function () {});

        $builder
            ->route('/\/cleaners\/index.php/')
            ->unless(function ($uri) {})
            ->handle(function () {});

        $this->assertEquals(2, $builder->getCollection()->count());
    }
}
