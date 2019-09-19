<?php

namespace Pyz\Zed\NxsLoggerCleaner\Communication;

use Pyz\Zed\NxsLogger\Business\NxsLoggerFacadeInterface;
use Pyz\Zed\NxsLoggerCleaner\NxsLoggerCleanerDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class NxsLoggerCleanerCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Shared\Zed\NxsLogger\Business\NxsLoggerFacadeInterface
     */
    public function getNxsLoggerFacade(): NxsLoggerFacadeInterface
    {
        return $this->getProvidedDependency(NxsLoggerCleanerDependencyProvider::NXS_LOGGER_FACADE);
    }
}
