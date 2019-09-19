<?php

namespace Pyz\Zed\LiselTime\Business;

interface LiselTimeFacadeInterface
{
    /**
     * @param array $request $request
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function storeRequest(array $request): bool;

    /**
     * @param string $stoppId
     *
     * @return string
     */
    public function getScemId(string $stoppId): string;
}
