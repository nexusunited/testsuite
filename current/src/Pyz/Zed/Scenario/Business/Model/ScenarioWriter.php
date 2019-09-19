<?php

namespace Pyz\Zed\Scenario\Business\Model;

use Generated\Shared\Transfer\AbstractScenarioTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ScenarioToCustomerTransfer;
use Orm\Zed\Scenario\Persistence\NxsScenario;
use Pyz\Zed\Scenario\Persistence\ScenarioQueryContainer;

class ScenarioWriter
{
    /**
     * @var \Shared\Zed\Scenario\Persistence\ScenarioQueryContainer
     */
    private $queryContainer;

    /**
     * @var \Shared\Zed\Scenario\Business\Model\ScenarioReader
     */
    private $scenarioReader;

    /**
     * @param \Shared\Zed\Scenario\Persistence\ScenarioQueryContainer $queryContainer
     * @param \Shared\Zed\Scenario\Business\Model\ScenarioReader $scenarioReader
     */
    public function __construct(ScenarioQueryContainer $queryContainer, ScenarioReader $scenarioReader)
    {
        $this->queryContainer = $queryContainer;
        $this->scenarioReader = $scenarioReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ScenarioToCustomerTransfer $transfer
     *
     * @return \Generated\Shared\Transfer\ScenarioToCustomerTransfer
     */
    public function saveScenarioToCustomer(ScenarioToCustomerTransfer $transfer): ScenarioToCustomerTransfer
    {
        $aScenarioList = $transfer->getAbstractScenario();
        foreach ($aScenarioList as $aScenarioTrans) {
            $this->saveScenario($aScenarioTrans, $transfer->getCustomer());
        }

        return $transfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function deleteScenariosForCustomer(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        $this->queryContainer->queryNxsScenarioToSpyCustomerQuery()
            ->findByIdFkCustomer($customerTransfer->getIdCustomer())
            ->delete();

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AbstractScenarioTransfer $aScenarioTran
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    private function saveScenario(AbstractScenarioTransfer $aScenarioTran, CustomerTransfer $customerTransfer): void
    {
        $scenarios = $this->scenarioReader->getScenarios();

        foreach ($scenarios as $scenario) {
            $this->storeScenarioMatch($aScenarioTran, $customerTransfer, $scenario);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Orm\Zed\Scenario\Persistence\NxsScenario $scenario
     *
     * @return void
     */
    private function saveNxsScenarioToSpyCustomerReference(CustomerTransfer $customerTransfer, NxsScenario $scenario): void
    {
        $this->queryContainer
            ->queryNxsScenarioToSpyCustomerQuery()
            ->filterByIdFkCustomer($customerTransfer->getIdCustomer())
            ->filterByIdFkNxsScenario($scenario->getIdNxsScenario())
            ->findOneOrCreate()
            ->setIdFkCustomer($customerTransfer->getIdCustomer())
            ->setIdFkNxsScenario($scenario->getIdNxsScenario())
            ->save();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Orm\Zed\Scenario\Persistence\NxsScenario $scenario
     *
     * @return void
     */
    private function deleteNxsScenarioToSpyCustomerReference(CustomerTransfer $customerTransfer, NxsScenario $scenario): void
    {
        $this->queryContainer
            ->queryNxsScenarioToSpyCustomerQuery()
            ->filterByIdFkCustomer($customerTransfer->getIdCustomer())
            ->filterByIdFkNxsScenario($scenario->getIdNxsScenario())
            ->find()
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\AbstractScenarioTransfer $aScenarioTran
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Orm\Zed\Scenario\Persistence\NxsScenario $scenario
     *
     * @return void
     */
    private function storeScenarioMatch(
        AbstractScenarioTransfer $aScenarioTran,
        CustomerTransfer $customerTransfer,
        NxsScenario $scenario
    ): void {
        if ($aScenarioTran->getScenarioName() === $scenario->getName()) {
            if ($aScenarioTran->getCheckbox()) {
                $this->saveNxsScenarioToSpyCustomerReference($customerTransfer, $scenario);
            } else {
                $this->deleteNxsScenarioToSpyCustomerReference($customerTransfer, $scenario);
            }
        }
    }
}
