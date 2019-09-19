<?php

namespace Pyz\Zed\LiselTime\Business\Model;

use Pyz\Zed\LiselTime\Business\Exception\TimeMessageNotFoundException;
use Pyz\Zed\LiselTime\Persistence\LiselTimeQueryContainer;

class LiselTimeReader
{
    /**
     * @var \Shared\Zed\LiselTime\Persistence\LiselTimeQueryContainer
     */
    private $queryContainer;

    /**
     * @param \Shared\Zed\LiselTime\Persistence\LiselTimeQueryContainer $queryContainer
     */
    public function __construct(
        LiselTimeQueryContainer $queryContainer
    ) {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param string $stoppId
     *
     * @throws \Shared\Zed\LiselTime\Business\Exception\TimeMessageNotFoundException
     *
     * @return string
     */
    public function getScemId(string $stoppId): string
    {
        $timeMessage = $this->queryContainer->getLastTimeMessage($stoppId);
        if ($timeMessage === null) {
            throw new TimeMessageNotFoundException($stoppId);
        }
        return $timeMessage->getScemId();
    }
}
