<?php

namespace Pyz\Zed\LiselAnalyser\Business\Model\Collector;

use PDO;
use Pyz\Shared\LiselAnalyser\LiselAnalyserConstants;
use Pyz\Shared\LiselStopStatus\LiselStopStatusConstants;
use Pyz\Zed\LiselAnalyser\LiselAnalyserConfigInterface;
use Pyz\Zed\LiselAnalyser\Persistence\LiselAnalyserQueryContainerInterface;

class IdleStopsCollector extends UnfinishedStopsCollector
{
    /**
     * @var \Shared\Zed\LiselAnalyser\LiselAnalyserConfigInterface
     */
    private $config;

    /**
     * @param \Shared\Zed\LiselAnalyser\Persistence\LiselAnalyserQueryContainerInterface $queryContainer
     * @param \Shared\Zed\LiselAnalyser\LiselAnalyserConfigInterface $config
     */
    public function __construct(LiselAnalyserQueryContainerInterface $queryContainer, LiselAnalyserConfigInterface $config)
    {
        $this->config = $config;
        parent::__construct($queryContainer);
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface[]|\Generated\Shared\Transfer\LiselEventTransfer[]
     */
    public function collect(): array
    {
        $stmt = $this
            ->queryContainer
            ->getConnection()
            ->prepare('SELECT nstop.stopp_id, ntime.eta as date_time
                                    FROM nxs_stop nstop
                                      LEFT JOIN nxs_time ntime ON ntime.stopp_id = nstop.stopp_id
                                      LEFT JOIN nxs_stop_status nstatus ON nstatus.stopp_id = nstop.stopp_id
                                    WHERE (nstatus.ata IS NULL OR nstatus.status = :continue)
                                          AND 
                                            (
                                               ((ntime.eta <= :idleDatetime) AND (ntime.eta >= nstop.pta))
                                            OR 
                                               ((nstop.pta <= :idleDatetime) AND (nstop.pta >= ntime.eta OR ntime.eta IS NULL))
                                            )
                                          ');
        $stmt->execute([
        'continue' => LiselStopStatusConstants::STATUS['ContinueETA'],
                        'idleDatetime' => date('Y-m-d H:i:s', strtotime($this->config->getMaxIdleTime()))]);
        return $this->stopArrayToList($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * @return string
     */
    public function collectorType(): string
    {
        return LiselAnalyserConstants::IDLE_STOPS_COLLECTOR;
    }
}
