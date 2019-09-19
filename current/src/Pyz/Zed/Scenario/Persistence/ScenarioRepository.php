<?php

namespace Pyz\Zed\Scenario\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Shared\Zed\Scenario\Persistence\ScenarioPersistenceFactory getFactory()
 */
class ScenarioRepository extends AbstractRepository implements ScenarioRepositoryInterface
{
    /**
     * @return \Generated\Shared\Transfer\NxsScenarioEntityTransfer[]
     */
    public function getScenarioTransfers(): array
    {
        $criteria = $this->getFactory()->createNxsScenarioQuery();

        return $this->buildQueryFromCriteria($criteria)->find();
    }
}
