<?php
namespace MyApp\Slim;

use Closure;
use LogicException;
use Nette\DI\Container;

class ApplicationFactory
{
    /**
     * @var Container
     */
    private $container;


    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return SlimApp
     */
    public function create(): SlimApp
    {
        $slimConfiguration = $this->getConfiguration('slimConfiguration');
        $slimApp = new SlimApp($slimConfiguration);

        $configuration = $this->getConfiguration('api');

        foreach ($configuration['routes'] as $route => $routeData) {
            $this->registerInvokableActionRoutes($slimApp, $route, $routeData);
        }

        foreach ($configuration['handlers'] as $handler => $serviceName) {
            $slimApp->getContainer()[$handler] = $this->getServiceProvider($serviceName);
        }

        return $slimApp;
    }

    /**
     * @param string $configurationCode
     * @return array
     * @throws LogicException
     */
    private function getConfiguration(string $configurationCode): array
    {
        $configurations = $this->container->getParameters();

        if (!isset($configurations[$configurationCode]) || !is_array($configurations[$configurationCode])) {
            throw new LogicException(sprintf('Missing %s configuration', $configurationCode));
        }

        return $configurations[$configurationCode];
    }

    /**
     * @param SlimApp $slimApp
     * @param array $routeData
     * @param string $routePattern
     */
    private function registerInvokableActionRoutes(SlimApp $slimApp, $routePattern, array $routeData)
    {
        foreach ($routeData as $method => $config) {
            $serviceName = $config['service'];
            $this->registerServiceIntoSlimContainer($slimApp, $serviceName);
            $routeToAdd = $slimApp->map([$method], $routePattern, $serviceName);

            if (isset($config['middleware']) && count($config['middleware']) > 0) {
                foreach ($config['middleware'] as $middleware) {
                    $slimContainer = $slimApp->getContainer();

                    if (!$slimContainer->has($middleware)) {
                        $this->registerServiceIntoSlimContainer($slimApp, $middleware);
                    }

                    $routeToAdd->add($middleware);
                }
            }

        }
    }

    /**
     * @param SlimApp $slimApp
     * @param string $serviceName
     */
    private function registerServiceIntoSlimContainer(SlimApp $slimApp, $serviceName)
    {
        $service = $this->getServiceProvider($serviceName);
        $slimApp->getContainer()[$serviceName] = $service;
    }

    /**
     * @param string $serviceName
     * @return Closure
     */
    private function getServiceProvider($serviceName)
    {
        return function () use ($serviceName) {
            $service = $this->container->getByType($serviceName, false);
            if ($service === null) {
                $service = $this->container->getService($serviceName);
            }

            return $service;
        };
    }
}
