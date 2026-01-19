<?php

return [
    'enable' => env('SEND_ERROR_LOG_SLACK', false),
    'webhook_url' => env('SLACK_NOTIFY_WEBHOOK_URL', ''),
];
