<?php

namespace Pyz\Zed\LiselToolbox\Communication;

use Pyz\Zed\DhlImport\Business\DhlImportFacadeInterface;
use Pyz\Zed\LiselAnalyser\Business\LiselAnalyserFacadeInterface;
use Pyz\Zed\LiselRequest\Business\LiselRequestFacade;
use Pyz\Zed\LiselToolbox\LiselToolboxDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Shared\Zed\LiselToolbox\LiselToolboxConfig getConfig()
 */
class LiselToolboxCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Shared\Zed\LiselRequest\Business\LiselRequestFacade
     */
    public function getLiselRequestFacade(): LiselRequestFacade
    {
        return $this->getProvidedDependency(LiselToolboxDependencyProvider::FACADE_LISEL_REQUEST);
    }

    /**
     * @return \Shared\Zed\LiselAnalyser\Business\LiselAnalyserFacadeInterface
     */
    public function getLiselAnalyserFacade(): LiselAnalyserFacadeInterface
    {
        return $this->getProvidedDependency(LiselToolboxDependencyProvider::LISEL_ANALYSER_FACADE);
    }

    /**
     * @return \Shared\Zed\DhlImport\Business\DhlImportFacadeInterface
     */
    public function getDhlImportFacade(): DhlImportFacadeInterface
    {
        return $this->getProvidedDependency(LiselToolboxDependencyProvider::DHL_IMPORT_FACADE);
    }
}
