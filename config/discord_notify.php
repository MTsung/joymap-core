<?php

return [
    'enable' => env('SEND_ERROR_LOG_DISCORD', false),
    'token' => env('DISCORD_NOTIFY_TOKEN', ''),
    'channel_id' => env('DISCORD_NOTIFY_CHANNEL_ID', ''),
    'tag_id' => env('DISCORD_NOTIFY_ROLE_ID', ''),
];