<?php

namespace Pyz\Zed\Scenario\Communication\Controller;

use Generated\Shared\Transfer\ScenarioToCustomerTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Shared\Zed\Scenario\Communication\ScenarioCommunicationFactory getFactory()
 * @method \Shared\Zed\Scenario\Persistence\ScenarioQueryContainer getQueryContainer()
 * @method \Shared\Zed\Scenario\Business\ScenarioFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\ScenarioToCustomerTransfer $transfer $transfer
     *
     * @return \Generated\Shared\Transfer\ScenarioToCustomerTransfer
     */
    public function getScenarioToCustomerAction(ScenarioToCustomerTransfer $transfer): ScenarioToCustomerTransfer
    {
        return $this->getFactory()->createScenarioReader()->getScenarioToCustomer($transfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ScenarioToCustomerTransfer $transfer $transfer
     *
     * @return \Generated\Shared\Transfer\ScenarioToCustomerTransfer
     */
    public function saveScenarioToCustomerAction(ScenarioToCustomerTransfer $transfer): ScenarioToCustomerTransfer
    {
        return $this->getFactory()->createScenarioWriter()->saveScenarioToCustomer($transfer);
    }
}
