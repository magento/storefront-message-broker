<?php

namespace Magento\CatalogMessageBroker\Model;

use Magento\Framework\Component\ComponentRegistrar;

class ModulesCollector
{
    /**
     * @var ComponentRegistrar
     */
    private $componentRegistrar;

    /**
     * ModulesCollector constructor.
     * @param ComponentRegistrar $componentRegistrar
     */
    public function __construct(ComponentRegistrar $componentRegistrar)
    {
        $this->componentRegistrar = $componentRegistrar;
    }

    public function execute(): array
    {
        $modules = [];
        foreach ($this->componentRegistrar->getPaths('module') as $key => $module) {
            $modules[$key] = 1;
        }

        return $modules;
    }
}