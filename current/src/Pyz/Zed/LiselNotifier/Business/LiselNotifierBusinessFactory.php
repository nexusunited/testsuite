<?php

namespace Pyz\Zed\LiselNotifier\Business;

use Pyz\Zed\CustomerImport\Business\CustomerImportFacadeInterface;
use Pyz\Zed\LiselNotifier\Business\Model\LiselNotificationSender;
use Pyz\Zed\LiselNotifier\LiselNotifierDependencyProvider;
use Pyz\Zed\LiselTime\Business\LiselTimeFacadeInterface;
use Pyz\Zed\LiselTour\Business\LiselTourFacadeInterface;
use Pyz\Zed\Scenario\Business\ScenarioFacadeInterface;
use Pyz\Zed\ScenarioLogger\Business\ScenarioLoggerFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Shared\Zed\LiselNotifier\LiselNotifierConfig getConfig()
 * @method \Shared\Zed\LiselNotifier\Persistence\LiselNotifierQueryContainer getQueryContainer()
 */
class LiselNotifierBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Shared\Zed\LiselNotifier\Business\Model\LiselNotificationSender
     */
    public function createLiseLNotifySender(): LiselNotificationSender
    {
        return new LiselNotificationSender(
            $this->getScenarioFacade(),
            $this->getCustomerImportFacade(),
            $this->getScenarioLoggerFacade(),
            $this->getLiselTourFacade(),
            $this->getLiselTimeFacade(),
            $this->getConfig()->getEventNotificationLimit(),
            $this->getQueryContainer(),
            $this->getConfig()
        );
    }

    /**
     * @return \Shared\Zed\Scenario\Business\ScenarioFacadeInterface
     */
    public function getScenarioFacade(): ScenarioFacadeInterface
    {
        return $this->getProvidedDependency(LiselNotifierDependencyProvider::SCENARIO_FACADE);
    }

    /**
     * @return \Shared\Zed\CustomerImport\Business\CustomerImportFacadeInterface
     */
    public function getCustomerImportFacade(): CustomerImportFacadeInterface
    {
        return $this->getProvidedDependency(LiselNotifierDependencyProvider::CUSTOMER_IMPORT_FACADE);
    }

    /**
     * @return \Shared\Zed\ScenarioLogger\Business\ScenarioLoggerFacadeInterface
     */
    public function getScenarioLoggerFacade(): ScenarioLoggerFacadeInterface
    {
        return $this->getProvidedDependency(LiselNotifierDependencyProvider::SCENARIO_LOGGER_FACADE);
    }

    /**
     * @return \Shared\Zed\LiselTour\Business\LiselTourFacadeInterface
     */
    public function getLiselTourFacade(): LiselTourFacadeInterface
    {
        return $this->getProvidedDependency(LiselNotifierDependencyProvider::LISEL_TOUR_FACADE);
    }

    /**
     * @return \Shared\Zed\LiselTime\Business\LiselTimeFacadeInterface
     */
    public function getLiselTimeFacade(): LiselTimeFacadeInterface
    {
        return $this->getProvidedDependency(LiselNotifierDependencyProvider::LISEL_TIME_FACADE);
    }
}
