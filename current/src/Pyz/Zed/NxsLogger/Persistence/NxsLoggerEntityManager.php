<?php

namespace Pyz\Zed\NxsLogger\Persistence;

use Orm\Zed\NxsLogger\Persistence\Map\NxsLoggingTableMap;
use PDO;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Propel;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use function date;
use function sprintf;
use function strtotime;

class NxsLoggerEntityManager extends AbstractEntityManager implements NxsLoggerEntityManagerInterface
{
    /**
     * @param string $range
     *
     * @return void
     */
    public function removeOldLoggingEntries(string $range): void
    {
        $con = Propel::getConnection();

        $sql = sprintf(
            'DELETE FROM %s WHERE %s %s ?',
            NxsLoggingTableMap::TABLE_NAME,
            NxsLoggingTableMap::COL_CREATED,
            Criteria::LESS_EQUAL
        );

        $query = $con->prepare($sql);
        $query->bindValue(1, date('Y-m-d', strtotime($range)), PDO::PARAM_STR);

        $query->execute();
    }
}
