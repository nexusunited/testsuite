<?php

namespace Pyz\Zed\LiselMonitoring\Communication;

use Pyz\Zed\LiselMonitoring\LiselMonitoringDependencyProvider;
use Pyz\Zed\LiselRequest\Business\LiselRequestFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Mail\Business\MailFacadeInterface;

/**
 * @method \Shared\Zed\LiselMonitoring\LiselMonitoringConfig getConfig()
 */
class LiselMonitoringCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Shared\Zed\LiselRequest\Business\LiselRequestFacadeInterface
     */
    public function getLiselRequestFacade(): LiselRequestFacadeInterface
    {
        return $this->getProvidedDependency(LiselMonitoringDependencyProvider::LISEL_REQUEST_FACADE);
    }

    /**
     * @return \Spryker\Zed\Mail\Business\MailFacadeInterface
     */
    public function getMailFacade(): MailFacadeInterface
    {
        return $this->getProvidedDependency(LiselMonitoringDependencyProvider::MAIL_FACADE);
    }
}
