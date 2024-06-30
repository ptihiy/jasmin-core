<?php

namespace Jasmin\Core\Kernel;

class Kernel
{
    protected array $config = [];

    public function __construct(private string $projectPath)
    {
        $this->createConfig();
    }

    public function getConfig(string $id)
    {
        // TODO: maybe instead of this dirty function we should populate config more nicely?
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
        $this->config = parse_ini_file($this->projectPath . '/conf.ini');
    }
}
