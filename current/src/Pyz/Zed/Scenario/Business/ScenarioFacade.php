<?php

namespace Pyz\Zed\Scenario\Business;

use Generated\Shared\Transfer\CommunicationDetailsTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Shared\Zed\Scenario\Persistence\ScenarioRepository getRepository()
 * @method \Shared\Zed\Scenario\Business\ScenarioBusinessFactory getFactory()
 */
class ScenarioFacade extends AbstractFacade implements ScenarioFacadeInterface
{
    /**
     * @param string $eventName $eventName
     * @param \Generated\Shared\Transfer\CommunicationDetailsTransfer $transferData
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function notifyCustomer(
        string $eventName,
        CommunicationDetailsTransfer $transferData,
        CustomerTransfer $customerTransfer
    ): void {
        $this->getFactory()
            ->createScenario()
            ->notifyCustomer($eventName, $transferData, $customerTransfer);
    }

    /**
     * @param string $eventName $eventName
     * @param \Generated\Shared\Transfer\CommunicationDetailsTransfer $transferData
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity
     *
     * @return void
     */
    public function notifyCustomerByEntity(
        string $eventName,
        CommunicationDetailsTransfer $transferData,
        SpyCustomer $customerEntity
    ): void {
        $this->getFactory()
            ->createScenario()
            ->notifyCustomerByEntity($eventName, $transferData, $customerEntity);
    }

    /**
     * @param int $idCustomer
     *
     * @return \Orm\Zed\Scenario\Persistence\NxsScenario[]
     */
    public function getCustomerAssignments(int $idCustomer): array
    {
        return $this->getFactory()
            ->createScenarioReader()
            ->getCustomerAssignments($idCustomer);
    }

    /**
     * @return \Generated\Shared\Transfer\NxsScenarioEntityTransfer[]
     */
    public function getScenarios(): array
    {
        return $this->getRepository()->getScenarioTransfers();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function deleteScenariosForCustomer(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        return $this->getFactory()
            ->createScenarioWriter()
            ->deleteScenariosForCustomer($customerTransfer);
    }
}
