<?php

namespace Pyz\Zed\LiselToolbox;

use Pyz\Shared\RestAuth\RestAuthConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class LiselToolboxConfig extends AbstractBundleConfig
{
    /**
     * @return array
     */
    public function getValidUsers(): array
    {
        return $this->get(RestAuthConstants::AUTH_KEY, []);
    }
}
