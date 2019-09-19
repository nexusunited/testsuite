<?php

namespace Pyz\Zed\ApiDocumentor\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Shared\Zed\ApiDocumentor\Business\ApiDocumentorBusinessFactory getFactory()
 */
class ApiDocumentorFacade extends AbstractFacade implements ApiDocumentorFacadeInterface
{
    /**
     * @param string $transfer $transfer
     *
     * @return array
     */
    public function getTransferAnnotations(string $transfer): array
    {
        return $this->getFactory()->createReflection()->getTransferAnnotations($transfer);
    }

    /**
     * @param string $abstractController $abstractController
     *
     * @return array
     */
    public function getControllerAnnotations(string $abstractController): array
    {
        return $this->getFactory()->createReflection()->getControllerAnnotations($abstractController);
    }
}
