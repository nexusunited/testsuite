<?php

namespace Pyz\Zed\ScenarioLogger\Business;

use Orm\Zed\ScenarioLogger\Persistence\Map\NxsScenarioLoggingTableMap;
use PDO;
use Pyz\Zed\ScenarioLogger\Persistence\ScenarioLoggerQueryContainerInterface;
use function sprintf;

/**
 * Class ScenarioLoggerReader
 *
 * @package Portal\Zed\ScenarioLogger\Business
 */
class ScenarioLoggerReader
{
    /**
     * @var \Shared\Zed\ScenarioLogger\Persistence\ScenarioLoggerQueryContainerInterface
     */
    private $scenarioLoggerQueryContainer;

    /**
     * @var string
     */
    private $storeName;

    /**
     * @param \Shared\Zed\ScenarioLogger\Persistence\ScenarioLoggerQueryContainerInterface $scenarioLoggerQuery
     * @param string $storeName
     */
    public function __construct(ScenarioLoggerQueryContainerInterface $scenarioLoggerQuery, string $storeName)
    {
        $this->scenarioLoggerQueryContainer = $scenarioLoggerQuery;
        $this->storeName = $storeName;
    }

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function getLogs(int $limit, int $offset): array
    {
        $stmt = $this
            ->scenarioLoggerQueryContainer
            ->getConnection()
            ->prepare($this->getQuery($limit, $offset));
        $stmt->execute([]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function getLogSummary(int $limit, int $offset): array
    {
        $stmt = $this
            ->scenarioLoggerQueryContainer
            ->getConnection()
            ->prepare($this->getLogSummaryQuery($limit, $offset));
        $stmt->execute([]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return int
     */
    public function getLogCount(): int
    {
        return $this->scenarioLoggerQueryContainer->queryNxsScenarioLoggingQuery()->count();
    }

    /**
     * @return int
     */
    public function getLogSummaryCount(): int
    {
        return $this->scenarioLoggerQueryContainer->queryNxsScenarioLoggingQuery()
            ->select(NxsScenarioLoggingTableMap::COL_STOPP_ID)
            ->distinct()
            ->count();
    }

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return string
     */
    protected function getQuery(int $limit, int $offset): string
    {
        $query = "SELECT 
                                    tour_number, 
                                    delivery_number, 
                                    stopp_id,
                                    scem_id, 
                                    customer_number,
                                    to_char(created_at, 'dd.MM.yyyy HH24:MI:SS') as createddate, 
                                    replace(event_name,'.event','') as event, 
                                    to_char(date_time, 'dd.MM.yyyy') || ' ' || to_char(date_time, 'HH24:MI:SS') as eventdate
                                    FROM nxs_scenario_logging ORDER BY created_at DESC";

        $query .= " LIMIT $limit OFFSET $offset";

        return $query;
    }

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return string
     */
    protected function getLogSummaryQuery(int $limit, int $offset): string
    {
        $query = "SELECT
           stopp_id,
           MIN(CASE WHEN event_name LIKE 'first_pta%' THEN date_time ELSE NULL END) as tour_date,
           GROUP_CONCAT(DISTINCT customer_number) as customer_id,
           '{$this->storeName}' as country,
           MIN(CASE WHEN event_name LIKE 'first_pta%' THEN to_char(date_time, 'dd.MM.yyyy HH24:MI:SS') ELSE NULL END) as first_pta,
           MIN(CASE WHEN event_name LIKE 'first_eta%' THEN to_char(date_time, 'dd.MM.yyyy HH24:MI:SS') ELSE NULL END) as first_eta,
           MAX(CASE WHEN event_name LIKE 'ata%' THEN to_char(date_time, 'dd.MM.yyyy HH24:MI:SS') ELSE NULL END) as ata,
           replace(to_char(ABS({$this->getDateTimeDiff('MIN(CASE WHEN event_name LIKE \'always_update_eta%\' THEN date_time ELSE NULL END) -
                         MAX(CASE WHEN event_name LIKE \'always_update_eta%\' THEN date_time ELSE NULL END)')}/1440), '0D999999999'), '.', ',') as min_max_eta_abs,
           replace(to_char(ABS({$this->getDateTimeDiff('MIN(CASE WHEN event_name LIKE \'first_eta%\' THEN date_time ELSE NULL END) -
                         MIN(CASE WHEN event_name LIKE \'first_pta%\' THEN date_time ELSE NULL END)')}/1440), '0D999999999'), '.', ',') as pta_to_eta_abs,
           replace(to_char(ABS({$this->getDateTimeDiff('MIN(CASE WHEN event_name LIKE \'ata%\' THEN date_time ELSE NULL END) -
                         MIN(CASE WHEN event_name LIKE \'first_pta%\' THEN date_time ELSE NULL END)')}/1440), '0D999999999'), '.', ',') as pta_to_ata_abs,
           replace(to_char(ABS({$this->getDateTimeDiff('MIN(CASE WHEN event_name LIKE \'ata%\' THEN date_time ELSE NULL END) -
                         MIN(CASE WHEN event_name LIKE \'first_eta%\' THEN date_time ELSE NULL END)')}/1440), '0D999999999'), '.', ',') as eta_to_ata_abs,
           SUM(CASE WHEN event_name LIKE 'always_update_eta.event' THEN 1 ELSE 0 END) as eta_count,
           GROUP_CONCAT(DISTINCT tour_number) as tour_id,
           replace(to_char({$this->getDateTimeDiff('MIN(CASE WHEN event_name LIKE \'ata%\' THEN date_time ELSE NULL END)-
                     MIN(CASE WHEN event_name LIKE \'first_pta%\' THEN date_time ELSE NULL END)')}/1440, '0D999999999'), '.', ',') as pta_to_ata
        FROM nxs_scenario_logging
        GROUP BY stopp_id";

        $query .= " LIMIT $limit OFFSET $offset";

        return $query;
    }

    /**
     * @param string $dateDiffLogic
     *
     * @return string
     */
    private function getDateTimeDiff(string $dateDiffLogic): string
    {
        return sprintf(
            "(DATE_PART('day', %s) * 1440 + DATE_PART('hour', %s) * 60 + DATE_PART('minute', %s))",
            $dateDiffLogic,
            $dateDiffLogic,
            $dateDiffLogic
        );
    }
}
