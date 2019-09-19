<?php

namespace Pyz\Zed\Lisel\Business\Actions;

use Pyz\Shared\LiselRequest\LiselRequestConstants;

class LiselTimeAction extends LiselAbstractAction
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return LiselRequestConstants::LISEL_TIME_TYPE;
    }
}
