<?php

namespace AspectMock\Proxy;

trait ClassHandlerTrait
{
    private $className;

    public function getClassName()
    {
        return $this->className;
    }

    protected function setClassName($className)
    {
        $this->className = $className;
    }
}