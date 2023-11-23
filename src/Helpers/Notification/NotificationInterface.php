<?php

namespace Mtsung\JoymapCore\Helpers\Notification;


interface NotificationInterface
{
    public function send(array $tokens, string $title, string $body, int $badge, array $data): bool;
}
