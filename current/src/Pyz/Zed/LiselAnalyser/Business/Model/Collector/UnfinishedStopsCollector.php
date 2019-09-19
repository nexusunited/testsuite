<?php

namespace Pyz\Zed\LiselAnalyser\Business\Model\Collector;

use Generated\Shared\Transfer\LiselEventTransfer;
use PDO;
use Pyz\Shared\LiselAnalyser\LiselAnalyserConstants;
use Pyz\Shared\LiselStopStatus\LiselStopStatusConstants;
use Pyz\Zed\LiselAnalyser\Persistence\LiselAnalyserQueryContainerInterface;

class UnfinishedStopsCollector implements CollectorInterface
{
    /**
     * @var \Shared\Zed\LiselAnalyser\Persistence\LiselAnalyserQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Shared\Zed\LiselAnalyser\Persistence\LiselAnalyserQueryContainerInterface $queryContainer
     */
    public function __construct(LiselAnalyserQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface[]|\Generated\Shared\Transfer\LiselEventTransfer[]
     */
    public function collect(): array
    {
        return $this->getUnfinishedStops();
    }

    /**
     * @return \Generated\Shared\Transfer\LiselEventTransfer[]
     */
    private function getUnfinishedStops(): array
    {
        $stmt = $this
            ->queryContainer
            ->getConnection()
            ->prepare('SELECT nstop.stopp_id, ntime.eta as date_time
                                    FROM nxs_stop nstop
                                      LEFT JOIN nxs_time ntime ON ntime.stopp_id = nstop.stopp_id
                                      LEFT JOIN nxs_stop_status nstatus ON nstatus.stopp_id = nstop.stopp_id
                                    WHERE (nstatus.ata IS NULL OR nstatus.status = :continue) AND ntime.eta IS NOT NULL');
        $stmt->execute(['continue' => LiselStopStatusConstants::STATUS['ContinueETA']]);
        $openStops = $this->stopArrayToList($stmt->fetchAll(PDO::FETCH_ASSOC));
        return $openStops;
    }

    /**
     * @param array $stopArray
     *
     * @return \Generated\Shared\Transfer\LiselEventTransfer[]
     */
    protected function stopArrayToList(array $stopArray): array
    {
        array_walk($stopArray, function (&$stopp) {
            $stopp = (new LiselEventTransfer())->fromArray($stopp, true);
        });
        return $stopArray;
    }

    /**
     * @return string
     */
    public function collectorType(): string
    {
        return LiselAnalyserConstants::UNFINISHED_STOPS_COLLECTOR;
    }
}
