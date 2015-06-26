<?php

namespace amonger\Firewall\Collection;

use amonger\Firewall\Firewall;
use ArrayAccess;
use closure;

class CollectionBuilder
{
    private $requestUri;
    private $conditions = array();
    private $routes = array();

    public function __construct(ArrayAccess $collection, $requestUri = "")
    {
        $this->collection = $collection;
        $this->requestUri = $requestUri;
    }

    public function setRequestUri($requestUri)
    {
        $this->requestUri = $requestUri;

        return $this;
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
        $this->conditions[] = $condition;

        return $this;
    }

    /**
     * @param callable $fn
     */
    public function handle(closure $fn)
    {
        $firewall = new Firewall($this->requestUri);
        foreach ($this->routes as $route) {
            $firewall->route($route);
        }

        foreach ($this->conditions as $condition) {
            $firewall->unless($condition);
        }

        $this->collection[] = $firewall->handle($fn);

        $this->routes = array();
        $this->conditions = array();
    }

    public function getCollection()
    {
        return $this->collection;
    }
}
