<?php

namespace Jasmin\Core\Kernel;

use Jasmin\Core\Request\Request;
use Jasmin\Core\Container\Container;
use Jasmin\Core\Container\ContainerInterface;
use Jasmin\Core\Request\RequestInterface;

abstract class Kernel implements KernelInterface
{
    protected ContainerInterface $container;

    protected RequestInterface $request;

    protected array $config = [];

    protected array $serviceProviders = [];

    public function __construct(protected string $dir)
    {
        $this->createConfig();

        $this->container = new Container();

        $this->container->add(RequestInterface::class, fn () => new Request($_SERVER, $_GET, $_POST));

        $this->request = $this->container->get(RequestInterface::class);
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    abstract public function start(): void;

    public function getConfig(string $id): mixed
    {
        $parts = explode('.', $id);
        $configScope = $this->config;
        foreach ($parts as $part) {
            if (array_key_exists($part, $configScope)) {
                $configScope = $configScope[$part];
            } else {
                return null;
            }
        }

        return $configScope;
    }

    private function createConfig()
    {
        $this->config = parse_ini_file($this->dir . '/conf.ini', true);
    }

    protected function addServiceProvider(string $id): void
    {
        $this->serviceProviders[] = $id;
    }

    protected function bootServices(): void
    {
        foreach ($this->serviceProviders as $serviceProviderId) {
            $serviceProvider = new $serviceProviderId($this);
            $serviceProvider->configure();
        }
    }
}
