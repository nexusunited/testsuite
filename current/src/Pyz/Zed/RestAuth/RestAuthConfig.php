<?php

namespace Pyz\Zed\RestAuth;

use Pyz\Shared\RestAuth\RestAuthConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class RestAuthConfig extends AbstractBundleConfig implements RestAuthConfigInterface
{
    /**
     * @return array
     */
    public function getValidUsers(): array
    {
        return $this->get(RestAuthConstants::AUTH_KEY, []);
    }

    /**
     * @return bool
     */
    public function getLogConfig(): bool
    {
        return $this->get(RestAuthConstants::LOG_REQUESTS, false);
    }
}
