<?php

namespace Pyz\Zed\Scenario\Business\Model;

use Generated\Shared\Transfer\CommunicationDetailsTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Scenario\Persistence\NxsScenario;
use Orm\Zed\Scenario\Persistence\NxsScenarioQuery;
use Orm\Zed\Scenario\Persistence\NxsScenarioToSpyCustomerQuery;
use Pyz\Zed\CommunicationBase\Business\CommunicationBaseFacadeInterface;
use Pyz\Zed\Scenario\ScenarioConfig;
use Spryker\Zed\Customer\Business\CustomerFacadeInterface;
use function in_array;

class Scenario
{
    /**
     * @var bool
     */
    private $eventMatchScenario;

    /**
     * @var \Shared\Zed\Scenario\ScenarioConfig
     */
    private $config;

    /**
     * @var \Shared\Zed\CommunicationBase\Business\CommunicationBaseFacadeInterface
     */
    private $communicationBaseFacade;

    /**
     * @var \Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    private $customerFacade;

    /**
     * @param \Shared\Zed\Scenario\ScenarioConfig $scenarioConfig
     * @param \Shared\Zed\CommunicationBase\Business\CommunicationBaseFacadeInterface $facade
     * @param \Spryker\Zed\Customer\Business\CustomerFacadeInterface $customerFacade
     */
    public function __construct(
        ScenarioConfig $scenarioConfig,
        CommunicationBaseFacadeInterface $facade,
        CustomerFacadeInterface $customerFacade
    ) {
        $this->config = $scenarioConfig;
        $this->communicationBaseFacade = $facade;
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param string $eventName $eventName
     * @param \Generated\Shared\Transfer\CommunicationDetailsTransfer $transferDetails
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function notifyCustomer(
        string $eventName,
        CommunicationDetailsTransfer $transferDetails,
        CustomerTransfer $customerTransfer
    ): void {
        $scenarioMap = $this->config->getScenarioDefinition();
        $this->eventMatchScenario = false;

        foreach ($scenarioMap as $scenarioName => $scenarioEvents) {
            $this->handleEventScenario($eventName, $transferDetails, $customerTransfer, $scenarioEvents, $scenarioName);
        }
    }

    /**
     * Wrapper method to ensure BC.
     *
     * @param string $eventName
     * @param \Generated\Shared\Transfer\CommunicationDetailsTransfer $transferDetails
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity
     *
     * @return void
     */
    public function notifyCustomerByEntity(
        string $eventName,
        CommunicationDetailsTransfer $transferDetails,
        SpyCustomer $customerEntity
    ): void {
        $customerTransfer = $this->customerFacade->getCustomer(
            (new CustomerTransfer())->setIdCustomer($customerEntity->getIdCustomer())
        );

        $this->notifyCustomer($eventName, $transferDetails, $customerTransfer);
    }

    /**
     * @param string $eventName $eventName
     * @param \Generated\Shared\Transfer\CommunicationDetailsTransfer $eventTransfer $eventTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    private function triggerCommunicationBase(
        string $eventName,
        CommunicationDetailsTransfer $eventTransfer,
        CustomerTransfer $customerTransfer
    ): void {
        $this->communicationBaseFacade->startCommunication($eventName, $customerTransfer, $eventTransfer);
    }

    /**
     * @param \Orm\Zed\Scenario\Persistence\NxsScenario $scenario
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    private function customerIsAssignedToNxsScenario(NxsScenario $scenario, CustomerTransfer $customerTransfer): bool
    {
        $isAssigned = false;
        $scenarioId = $scenario->getIdNxsScenario();

        $nxsScenarioTc = NxsScenarioToSpyCustomerQuery::create()
            ->filterByIdFkCustomer($customerTransfer->getIdCustomer())
            ->filterByIdFkNxsScenario($scenarioId)
            ->find()
            ->getFirst();

        if ($nxsScenarioTc !== null) {
            $isAssigned = true;
        }
        return $isAssigned;
    }

    /**
     * @param string $scenarioName $scenarioName
     *
     * @return \Orm\Zed\Scenario\Persistence\NxsScenario
     */
    private function getNxsScenarioByName(string $scenarioName): NxsScenario
    {
        $scenario = NxsScenarioQuery::create()
            ->findOneByName($scenarioName);
        return $scenario;
    }

    /**
     * @param string $eventName $eventName
     * @param \Generated\Shared\Transfer\CommunicationDetailsTransfer $transferDetails $transferDetails
     * @param \Generated\Shared\Transfer\CustomerTransfer $customer $customer
     * @param array $scenarioEvents $scenarioEvents
     * @param string $scenarioName $scenarioName
     *
     * @return void
     */
    private function handleEventScenario(
        string $eventName,
        CommunicationDetailsTransfer $transferDetails,
        CustomerTransfer $customer,
        array $scenarioEvents,
        string $scenarioName
    ): void {
        $nxsScenario = $this->getNxsScenarioByName($scenarioName);

        if ($nxsScenario === null) {
            return;
        }

        if ($customer === null) {
            return;
        }

        if ($this->eventIsInScenarioDefinition($eventName, $scenarioEvents) === false) {
            return;
        }

        if ($this->customerIsAssignedToNxsScenario($nxsScenario, $customer) === false) {
            return;
        }

        $this->triggerCommunicationBase($eventName, $transferDetails, $customer);
        $this->eventMatchScenario = true;
    }

    /**
     * @param string $eventName $eventName
     * @param array $scenarioEvents $scenarioEvents
     *
     * @return bool
     */
    private function eventIsInScenarioDefinition(string $eventName, array $scenarioEvents): bool
    {
        $match = false;

        if (!$this->eventMatchScenario && in_array($eventName, $scenarioEvents, true)) {
            $match = true;
        }

        return $match;
    }
}
