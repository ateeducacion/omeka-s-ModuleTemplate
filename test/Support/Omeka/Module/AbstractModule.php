<?php
declare(strict_types=1);

namespace Omeka\Module;

abstract class AbstractModule
{
    protected $serviceLocator;

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    abstract public function getConfig();
}
