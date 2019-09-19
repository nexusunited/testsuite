<?php

namespace Pyz\Shared\EmailChannel;

interface EmailChannelConstants
{
    public const DEVELOPMENT_SENDER = 'DEVELOPMENT_SENDER';
    public const EMAIL_CHANNEL_QUEUE = 'email.event.queue';
    public const EMAIL_CHANNEL_ERROR_QUEUE = 'email.event.queue.error';
    public const LL_HELPDESK_EMAIL = 'LL_HELPDESK_EMAIL';
}
