<?php

namespace Pyz\Zed\LiselRequest\Business\Model;

interface LiselLastRequestTimestampInterface
{
    /**
     * @return void
     */
    public function setTimestamp(): void;

    /**
     * @return int|null
     */
    public function getTimestamp(): ?int;
}
