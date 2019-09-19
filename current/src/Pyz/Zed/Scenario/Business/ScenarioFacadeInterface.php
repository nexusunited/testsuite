<?php

namespace Pyz\Zed\Scenario\Business;

use Generated\Shared\Transfer\CommunicationDetailsTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;

interface ScenarioFacadeInterface
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
    ): void;

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
    ): void;

    /**
     * @param int $idCustomer
     *
     * @return \Orm\Zed\Scenario\Persistence\NxsScenario[]
     */
    public function getCustomerAssignments(int $idCustomer): array;

    /**
     * @return \Generated\Shared\Transfer\NxsScenarioEntityTransfer[]
     */
    public function getScenarios(): array;

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function deleteScenariosForCustomer(CustomerTransfer $customerTransfer): CustomerTransfer;
}
