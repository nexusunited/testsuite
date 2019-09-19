<?php

namespace Pyz\Zed\RestAuth;

interface RestAuthConfigInterface
{
    /**
     * @return array
     */
    public function getValidUsers(): array;

    /**
     * @return bool
     */
    public function getLogConfig(): bool;
}
