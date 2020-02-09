<?php declare(strict_types=1);

namespace App\Infrastructure;

use App\Infrastructure\Exception\DependencyContainerException;
use App\Infrastructure\Exception\RouteNotFound;
use RuntimeException;

final class Application
{
    private array $routes = [];
    private array $dependencies;
    private string $currentRoute;
    private RouteParser $routeParser;
    private ProcessorArgsParser $processorArgsParser;
    private ResponseRenderer $responseRenderer;

    public function __construct(
        array $dependencies,
        string $routeParserClass,
        string $processorArgsParserClass,
        string $responseRendererClass
    )
    {
        foreach ($dependencies as $key => $dependency) {
            if (!is_callable($dependency)) {
                throw new RuntimeException("Dependency factory does not callable: key $key");
            }
        }

        $this->dependencies = $dependencies;
        $this->routeParser = $this->buildObject($routeParserClass);
        $this->processorArgsParser = $this->buildObject($processorArgsParserClass);
        $this->responseRenderer = $this->buildObject($responseRendererClass);
    }

    /**
     * @param string $dependencyKey
     * @return object
     * @throws DependencyContainerException
     */
    public function buildObject(string $dependencyKey): object
    {
        if (!array_key_exists($dependencyKey, $this->dependencies)) {
            throw DependencyContainerException::createDefault($dependencyKey);
        }

        $closure = $this->dependencies[$dependencyKey];

        return $closure($this);
    }

    /**
     * @throws DependencyContainerException
     * @throws RouteNotFound
     */
    public function run(): void
    {
        $this->currentRoute = $this->routeParser->parse($this->routes);

        $processor = $this->prepareProcessor();

        $response = $processor(...$this->processorArgsParser->parse());

        $this->responseRenderer->render($response);
        exit;
    }

    /**
     * @return mixed|object
     * @throws DependencyContainerException
     */
    public function prepareProcessor(): callable
    {
        $routeProcessorClass = $this->routes[$this->currentRoute];

        $processor = is_string($routeProcessorClass) ? $this->buildObject($routeProcessorClass) : $routeProcessorClass;

        if (!is_callable($processor)) {
            throw new RuntimeException('Processor does not callable');
        }
        return $processor;
    }

    /**
     * @param string $routePath
     * @param string $routeProcessorClass class name of callable class
     * @return $this
     */
    public function addRoute(string $routePath, string $routeProcessorClass): self
    {
        $routePath = rtrim($routePath, '/');

        if (!class_exists($routeProcessorClass)) {
            throw new RuntimeException('Class does not exists');
        }
        $this->routes[$routePath] = $routeProcessorClass;

        return $this;
    }
}