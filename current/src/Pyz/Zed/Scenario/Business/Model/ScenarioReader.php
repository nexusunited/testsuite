<?php

namespace Pyz\Zed\Scenario\Business\Model;

use Generated\Shared\Transfer\AbstractScenarioTransfer;
use Generated\Shared\Transfer\ScenarioToCustomerTransfer;
use PDO;
use Propel\Runtime\Collection\ObjectCollection;
use Pyz\Zed\Scenario\Persistence\ScenarioQueryContainer;

class ScenarioReader
{
    /**
     * @var \Shared\Zed\Scenario\Persistence\ScenarioQueryContainer
     */
    private $queryContainer;

    /**
     * @param \Shared\Zed\Scenario\Persistence\ScenarioQueryContainer $queryContainer
     */
    public function __construct(ScenarioQueryContainer $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @return \Orm\Zed\Scenario\Persistence\NxsScenario[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function getScenarios(): ObjectCollection
    {
        return $this->queryContainer->queryNxsScenarioQuery()->find();
    }

    /**
     * @param \Generated\Shared\Transfer\ScenarioToCustomerTransfer $scenarioToCustomerTransfer $scenarioToCustomerTransfer
     *
     * @return \Generated\Shared\Transfer\ScenarioToCustomerTransfer
     */
    public function getScenarioToCustomer(ScenarioToCustomerTransfer $scenarioToCustomerTransfer): ScenarioToCustomerTransfer
    {
        $data = $this->getCustomerAssignments($scenarioToCustomerTransfer->getCustomer()->getIdCustomer());
        $scenarioToCustomerTransfer = $this->addToScenarioCustomerTransfer($scenarioToCustomerTransfer, $data);

        return $scenarioToCustomerTransfer;
    }

    /**
     * @param int $idCustomer
     *
     * @return array
     */
    public function getCustomerAssignments(int $idCustomer): array
    {
        $stmt = $this
            ->queryContainer
            ->getConnection()
            ->prepare('SELECT * FROM nxs_scenario nx 
                                LEFT JOIN nxs_scenario_to_spy_customer nxc 
                                ON nxc.id_fk_nxs_scenario = nx.id_nxs_scenario 
                                AND nxc.id_fk_customer = :cid');
        $stmt->execute([':cid' => $idCustomer]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param \Generated\Shared\Transfer\ScenarioToCustomerTransfer $scenarioToCustomerTransfer $scenarioToCustomerTransfer
     * @param array $data $data
     *
     * @return \Generated\Shared\Transfer\ScenarioToCustomerTransfer
     */
    private function addToScenarioCustomerTransfer(ScenarioToCustomerTransfer $scenarioToCustomerTransfer, array $data): ScenarioToCustomerTransfer
    {
        foreach ($data as $abstractScenario) {
            $abstractScenarioTransfer = new AbstractScenarioTransfer();

            $checkbox = $this->checkScenarioHasCustomer($abstractScenario);
            $abstractScenarioTransfer->setCheckbox($checkbox);
            $abstractScenarioTransfer->setScenarioName($abstractScenario['name']);
            $scenarioToCustomerTransfer->addAbstractScenario($abstractScenarioTransfer);
        }

        return $scenarioToCustomerTransfer;
    }

    /**
     * @param array $abstractScenario $abstractScenario
     *
     * @return bool
     */
    private function checkScenarioHasCustomer(array $abstractScenario): bool
    {
        $checkbox = true;
        if ($abstractScenario['id_fk_customer'] === null) {
            $checkbox = false;
        }

        return $checkbox;
    }
}
