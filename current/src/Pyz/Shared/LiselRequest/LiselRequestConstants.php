<?php

namespace Pyz\Shared\LiselRequest;

interface LiselRequestConstants
{
    public const LISEL_TOUR_TYPE = 'LISEL_TOUR_TYPE';
    public const LISEL_TIME_TYPE = 'LISEL_TIME_TYPE';
    public const LISEL_STATUS_TYPE = 'LISEL_STATUS_TYPE';
    public const LISEL_AUTO_CONVERT = 'LISEL_AUTO_CONVERT';
    public const QUEUE_MESSAGE_CHUNK_SIZE = 'QUEUE_MESSAGE_CHUNK_SIZE';

    public const LISEL_REQUEST_QUEUE = 'lisel.request';
    public const LISEL_REQUEST_QUEUE_ERROR = 'lisel.request.error';

    public const LISEL_REQUEST_TIMESTAMP_STORAGE_KEY = 'lisel_request_timestamp';
}
