<?php

namespace Pyz\Zed\ApiDocumentor\Business;

use Pyz\Zed\ApiDocumentor\Business\Model\Reflection;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class ApiDocumentorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Shared\Zed\ApiDocumentor\Business\Model\Reflection
     */
    public function createReflection(): Reflection
    {
        return new Reflection();
    }
}
