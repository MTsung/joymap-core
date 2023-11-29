<?php

return [
    'enable' => env('SEND_ERROR_LOG_LINE', false),
    'url' => 'https://notify-api.line.me/api/notify',
    'token' => env('LINE_NOTIFY_TOKEN', ''),
];
