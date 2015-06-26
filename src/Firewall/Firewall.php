<?php

namespace amonger\Firewall;

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
        foreach ($this->routes as $route) {
            if (preg_match($route, $this->uri) && !$this->isAuthorised()) {
                $fn();
                return true;
            }
        }
        return false;
    }
}
