<?php

namespace App;

use App\Web\Request;
use Closure;
use RuntimeException;

class Application
{
    private $routes = [];
    private $dependencies;
    private $filters;
    private $currentRoute;

    /**
     * Application constructor.
     * @param Closure[]
     */
    public function __construct($dependencies)
    {
        foreach ($dependencies as $key => $dependency) {
            if (!is_callable($dependency)) {
                throw new RuntimeException("Dependency factory does not callable: key $key");
            }
        }

        $this->dependencies = $dependencies;
    }

    /**
     * @return void
     */
    public function run()
    {
        $this->detectRoute();

        if (!$this->currentRoute) {
            throw new RuntimeException('Page not found');
        }

        $this->filterRequest();

        $processor = $this->prepareProcessor();

        $request = $this->buildRequest();

        echo $processor($request);
        exit;
    }

    /**
     * @param $routePath
     * @param string $routeProcessor class name of processor
     * @param null|string $filterClass
     * @return Application
     */
    public function route($routePath, $routeProcessor, $filterClass = null)
    {
        if (!class_exists($routeProcessor)) {
            throw new RuntimeException('Route processor class not exists');
        }

        if ($filterClass && !class_exists($filterClass)) {
            throw new RuntimeException('Filter class not exists');
        }

        $routePath = rtrim($routePath, '/') . '/';

        $this->routes[$routePath] = $routeProcessor;

        if ($filterClass) {
            $this->filters[$routePath] = $filterClass;
        }

        return $this;
    }

    /**
     * @param  string $dependencyKey
     * @return object
     */
    public function buildObject($dependencyKey)
    {
        if (!array_key_exists($dependencyKey, $this->dependencies)) {
            throw new RuntimeException("Can`t find dependency $dependencyKey");
        }

        $closure = $this->dependencies[$dependencyKey];

        return $closure($this);
    }

    /**
     * @return Request
     */
    private function buildRequest()
    {
        $requestUri = $this->getUri();
        $requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
        $requestData = $requestMethod === 'post' ? $_POST : $_GET;
        $params = $this->parseUriParams();

        return new Request($requestMethod, $requestUri, $requestData, $params, $_FILES);
    }

    /**
     * @return array
     */
    private function parseUriParams()
    {
        $paramsInfo = $this->explodeParamsFromRoute();

        $uriParts = explode('/', $this->getUri());

        $params = [];

        foreach ($paramsInfo as $paramIndex => $paramName) {
            $params[$paramName] = $uriParts[$paramIndex];
        }

        return $params;
    }

    /**
     * @return string|null
     */
    private function detectRoute()
    {
        $requestUri = rtrim($_SERVER['REQUEST_URI'], '/') . '/';

        foreach (array_keys($this->routes) as $pattern) {
            $updatedPattern = str_replace('/', '\/', $pattern);
            $updatedPattern = preg_replace('/\{\w+\}/', "\w+", $updatedPattern);

            if (preg_match("/^$updatedPattern$/", $requestUri, $asd)) {
                $this->currentRoute = $pattern;
                return;
            }

        }

        return null;
    }

    private function filterRequest()
    {
        if (array_key_exists($this->currentRoute, $this->filters)) {
            $filter = $this->buildObject($this->filters[$this->currentRoute]);

            if (!is_callable($filter)) {
                throw new RuntimeException('Wrong processor type');
            }

            $filter();
        }
    }

    /**
     * @return array
     */
    private function explodeParamsFromRoute()
    {
        $params = [];
        foreach (explode('/', $this->currentRoute) as $i => $item) {
            if (strpos($item, '{') !== 0) {
                continue;
            }

            $params[$i] = trim($item, '{}');
        }

        return $params;
    }

    /**
     * @return mixed|object
     */
    public function prepareProcessor()
    {
        $routeProcessorClass = $this->routes[$this->currentRoute];

        $processor = is_string($routeProcessorClass) ? $this->buildObject($routeProcessorClass) : $routeProcessorClass;

        if (!is_callable($processor)) {
            throw new RuntimeException('Wrong processor type');
        }
        return $processor;
    }

    /**
     * @return string
     */
    private function getUri()
    {
        return rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/') . '/';
    }
}