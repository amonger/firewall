<?php

namespace amonger\Firewall;

use amonger\Firewall\Collection\Collection;
use amonger\Firewall\Collection\CollectionBuilder;
use closure;

/**
 * This is a simple firewall which checks whether a uri matches a route
 * then executes a callback if it is true.
 *
 * Class Firewall
 */
class Firewall
{
    private $routes;
    private $handler;
    private $conditions = array();

    /**
     * @param string $uri
     */
    public function __construct($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @param string $route
     * @return Firewall $this
     */
    public function route($route)
    {
        $this->routes[] = $route;
        return $this;
    }

    /**
     * @param callable $condition
     * @return Firewall $this
     */
    public function unless(closure $condition)
    {
        $this->conditions[] = $condition($this->uri);
        return $this;
    }

    /**
     * @return bool
     */
    private function isAuthorised()
    {
        return in_array(true, $this->conditions);
    }

    /**
     * @param callable $fn
     */
    public function handle(closure $fn)
    {
        $this->handler = $fn;

        return $this;
    }

    public function execute()
    {
        foreach ($this->routes as $route) {
            if (preg_match($route, $this->uri) && !$this->isAuthorised()) {
                $handler = $this->handler;
                $handler();

                return true;
            }
        }

        return false;
    }

    public static function getBuilder()
    {
        return new CollectionBuilder(new Collection());
    }

    public static function run(CollectionBuilder $firewallCollection)
    {
        $collection = $firewallCollection->getCollection();
        for($i = 0; $i < $collection->count(); $i++ ) {
            $collection[$i]->execute();
        }
    }
}
