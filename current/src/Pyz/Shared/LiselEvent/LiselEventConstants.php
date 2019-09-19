<?php

namespace Pyz\Shared\LiselEvent;

class LiselEventConstants
{
    /**
     * PTA - planned time of arrival
     */
    public const FIRST_PTA = 'first_pta.event';
    public const UPDATE_PTA = 'update_pta.event';

    /**
     * ETA - estimated time of arrival
     */
    public const FIRST_ETA = 'first_eta.event';
    public const SHORT_BEFORE_DELIVERY = 'short_before_delivery.event';
    public const ALWAYS_UPDATE_ETA_EVENT = 'always_update_eta.event';
    public const ETA_UPDATE_BEFORE_DELIVERY = 'eta.update_before_delivery.event';
    public const ETA_UPDATE_DELAY = 'eta.update_delay.event';
    public const ETA_DTF_DELAY = 'eta.dtf_delay.event';

    /**
     * ATA - actual time of arrival
     */
    public const LISEL_ATA = 'ata.event';
    public const LISEL_NEW_DELIVERY_STATUS = 'new_delivery_status.event';
}
