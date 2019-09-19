<?php

namespace Pyz\Zed\LiselTime\Persistence;

use Generated\Shared\Transfer\LiselTimeTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Shared\Zed\LiselTime\Persistence\LiselTimePersistenceFactory getFactory()
 */
class LiselTimeRepository extends AbstractRepository implements LiselTimeRepositoryInterface
{
    /**
     * @param string $stoppId
     *
     * @return \Generated\Shared\Transfer\LiselTimeTransfer|null
     */
    public function getLiselTimeByStoppId(string $stoppId): ?LiselTimeTransfer
    {
        $liselTime = $this->getFactory()
            ->createNxsTimeQuery()
            ->filterByStoppId($stoppId)
            ->findOne();

        if (!$liselTime) {
            return null;
        }

        return (new LiselTimeTransfer())->fromArray($liselTime->toArray(), true);
    }
}
