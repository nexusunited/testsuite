<?php

namespace Pyz\Shared\LiselStopStatus;

class LiselStopStatusConstants
{
    public const ETA_UPDATE_SHORT_BEFORE_MIN = 'ETA_UPDATE_SHORT_BEFORE';
    public const STATUS = [
                    'Delivered' => 0,
                    'Refused' => 5,
                    'NoOneThere' => 10,
                    'NoMoney' => 15,
                    'NoTimeLeft' => 20,
                    'NotEtaRelevant' => 25,
                    'ContinueETA' => 30,
                    'Tour_Cancellation' => 35,
                    'Idle' => 110,
    ];
}
