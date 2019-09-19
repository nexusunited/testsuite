<?php

namespace Pyz\Zed\Lisel\Business\Actions;

use Pyz\Shared\LiselRequest\LiselRequestConstants;

class LiselStatusAction extends LiselAbstractAction
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return LiselRequestConstants::LISEL_STATUS_TYPE;
    }
}
